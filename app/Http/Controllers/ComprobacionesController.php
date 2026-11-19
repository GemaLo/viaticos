<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Exception;
use Carbon\Carbon;
use SimpleXMLElement;
use App\Models\Files;
use App\Models\Factura;
use App\Models\Comision;
use App\Models\Reintegro;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Services\SatCfdiValidator;
use App\Models\GastosSinComprobante;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\File;

class ComprobacionesController extends Controller
{
    private SatCfdiValidator $sat;

    public function __construct(SatCfdiValidator $sat)
    {
        $this->sat = $sat;
    }

    public function comprobacionForm($idComisionHasheado)
    {
        try {
            $idComision = Crypt::decryptString($idComisionHasheado);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'El identificador de comisión no es válido.');
        }

        $query = Comision::where('idcomision', $idComision)->firstOrFail();

        $idstatuscomision = [8, 9, 10, 11, 17, 19, 20];

        if (!in_array($query->idstatuscomision, $idstatuscomision)) {
            return redirect()
                ->back()
                ->with('warning', 'No es posible tener acceso a esta página ya que la comisión no se encuentra en el estatus correspondiente para comprobar.');
        }

        // $usuarioLogueado = auth()->user();

        // if ($query->idpersoncomision !== $usuarioLogueado->idpersoncomision) {
        //     return redirect()
        //         ->back()
        //         ->with('warning', 'No tienes permisos para ver esta comisión.');
        // }

        $facturasAcumuladas = Factura::where('idcomision', $idComision)
            ->where('cancelacion', '!=', '1')
            ->where('idtypefactura', '!=', '7')
            ->sum('importe');

        $gastosSinComprobanteTotal = GastosSinComprobante::where('idcomision', $idComision)
            ->where('activo', '=', 1)
            ->sum('monto');

        $importeTotalComprobado = $facturasAcumuladas + $gastosSinComprobanteTotal;

        return view('comprobaciones.comprobacionForm', compact(
            'query',
            'facturasAcumuladas',
            'gastosSinComprobanteTotal',
            'importeTotalComprobado',
            'idComision'
        ));
    }

    public function processMultipleXmls(Request $request): JsonResponse
    {
        $request->validate([
            'initialDate' => 'required|date_format:Y-m-d',
            'finalDate' => 'required|date_format:Y-m-d|after_or_equal:initialDate',
            'idcomision' => 'required|integer|exists:comisiones,idcomision',
            'id_factura_reemplazo' => 'sometimes|nullable|integer|exists:facturas,idfactura',
            'file' => 'required|mimetypes:application/xml,text/xml,text/plain,application/pdf|max:10240',
        ], [
            'initialDate.required' => 'La fecha inicial de la comisión es requerida.',
            'initialDate.date_format' => 'La fecha inicial de la comisión debe tener formato YYYY-MM-DD.',
            'finalDate.required' => 'La fecha final de la comisión es requerida.',
            'finalDate.date_format' => 'La fecha final de la comisión debe tener formato YYYY-MM-DD.',
            'finalDate.after_or_equal' => 'La fecha final de la comisión no puede ser anterior a la fecha inicial.',
            'idcomision.required' => 'El ID de la comisión es requerido.',
            'idcomision.integer' => 'El ID de la comisión debe ser un número entero.',
            'idcomision.exists' => 'El ID de la comisión proporcionado no existe.',
            'file.required' => 'No se ha seleccionado ningún archivo.',
            'file.max' => 'El archivo no debe exceder los 10MB.',
            'file.mimetypes' => 'El archivo debe ser de tipo XML o PDF.',
        ]);

        $commissionStartDate = Carbon::createFromFormat('Y-m-d', $request->input('initialDate'))->startOfDay();
        $commissionEndDate = Carbon::createFromFormat('Y-m-d', $request->input('finalDate'))->endOfDay();
        $idComision = $request->input('idcomision');
        $idFacturaReemplazada = $request->input('id_factura_reemplazo');

        $comision = Comision::find($idComision);

        if (!$comision) {
            return response()->json([
                'status' => 'error',
                'filename' => 'Desconocido',
                'message' => 'Error interno: No se encontró la comisión con ID ' . $idComision
            ], 500);
        }

        $importeTotalComision = (float) ($comision->importe ?? 0);

        $importeGastado = Factura::where('idcomision', $idComision)->where('idtypefactura', '!=', 7)->where('cancelacion', '=', 2)->sum('importe');

        if ($idFacturaReemplazada) {
            $facturaAReemplazar = Factura::find($idFacturaReemplazada);

            if ($facturaAReemplazar) {
                $oldImporte = $facturaAReemplazar->importe ?? 0;
                $importeGastado = round($importeGastado - $oldImporte, 2);
            }
        }

        $saldoDisponible = $importeTotalComision - $importeGastado;
        $saldoDisponible = max(0, $saldoDisponible);

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'xml') {
            try {
                $xmlContent = file_get_contents($file->getRealPath());
                $xml = new SimpleXMLElement($xmlContent);

                $namespaces = $xml->getDocNamespaces(true);

                foreach ($namespaces as $prefix => $uri) {
                    if ($uri === 'http://www.sat.gob.mx/cfd/3') {
                        $xml->registerXPathNamespace('cfdi', $uri);
                    } elseif ($uri === 'http://www.sat.gob.mx/TimbreFiscalDigital') {
                        $xml->registerXPathNamespace('tfd', $uri);
                    } elseif ($uri === 'http://www.sat.gob.mx/cfd/4') {
                        $xml->registerXPathNamespace('cfdi', $uri);
                    }
                }

                if (isset($namespaces['']) && in_array($namespaces[''], ['http://www.sat.gob.mx/cfd/3', 'http://www.sat.gob.mx/cfd/4'])) {
                    $xml->registerXPathNamespace('cfdi', $namespaces['']);
                }

                $xmlDateString = '';
                $datePaths = [
                    '//cfdi:Comprobante/@Fecha',
                    '//tfd:TimbreFiscalDigital/@FechaTimbrado',
                    '//@Fecha',
                    '//@fecha',
                ];

                foreach ($datePaths as $path) {
                    $dateNodes = $xml->xpath($path);
                    if (!empty($dateNodes)) {
                        $xmlDateString = (string) $dateNodes[0];
                        break;
                    }
                }

                if (empty($xmlDateString)) {
                    return response()->json([
                        'status' => 'error',
                        'filename' => $filename,
                        'message' => 'No se encontró una etiqueta de fecha válida en el XML. Verifique su estructura.'
                    ], 400);
                }

                try {
                    $xmlDate = Carbon::parse($xmlDateString);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 'error',
                        'filename' => $filename,
                        'message' => 'Formato de fecha inválido en el XML (' . $xmlDateString . '): ' . $e->getMessage()
                    ], 400);
                }

                $extractedData = $this->extractAllCfdiData($xml);
                $uuid = strtoupper($extractedData['timbreFiscalDigital']['uuid'] ?? '');
                $totalFactura = $extractedData['comprobante']['total'] ?? '0.00';
                $totalFacturaFloat = (float) $totalFactura;
                $rfcEmisor = $extractedData['emisor']['rfc'] ?? '';
                $rfcReceptor = $extractedData['receptor']['rfc'] ?? '';
                $regimenReceptor = $extractedData['receptor']['regimenFiscalReceptor'] ?? null;

                if (empty($uuid)) {
                    return response()->json([
                        'status' => 'error',
                        'filename' => $filename,
                        'message' => 'No se pudo extraer el UUID del XML. Archivo inválido o estructura inesperada.'
                    ], 400);
                }
                $facturaExistente = Factura::where('cfdi', $uuid)->first();

                if ($facturaExistente) {
                    if ((int)$facturaExistente->cancelacion !== 1) {
                        return response()->json([
                            'status' => 'error',
                            'filename' => $filename,
                            'message' => "La factura con UUID {$uuid} ya ha sido registrada y se encuentra vigente. No se puede duplicar."
                        ], 409);
                    }
                }
                $requiredRfcReceptor = "SSP190501626";
                if (strtoupper($rfcReceptor) !== strtoupper($requiredRfcReceptor)) {
                    return response()->json([
                        'status' => 'error',
                        'filename' => $filename,
                        'message' => "El RFC del receptor del CFDI ({$rfcReceptor}) no coincide con el RFC requerido ({$requiredRfcReceptor})."
                    ], 403);
                }

                if (strtoupper($regimenReceptor) !== '603') {
                    return response()->json([
                        'status' => 'error',
                        'filename' => $filename,
                        'message' => "El Régimen Fiscal Receptor no es el requerido para procesar la factura el requerido es: '603 - Personas Morales con Fines no Lucrativos'."
                    ], 403);
                }
                $esPeaje = false;
                $clavePeajeSAT = ['95111603', '95111602', '95111609', '95111610', '95111615', '78111802', '95111600'];

                foreach ($extractedData['conceptos'] as $concepto) {
                    $claveActual = trim($concepto['ClaveProdServ'] ?? $concepto['claveProdServ'] ?? '');

                    $descripcionRaw = $concepto['descripcion'] ?? $concepto['Descripcion'] ?? '';
                    $descripcion = strtoupper(strtr(
                        utf8_decode($descripcionRaw),
                        'áéíóúüñÁÉÍÓÚÜÑ',
                        'AEIOUUNAEIOUUN'
                    ));

                    if (
                        in_array($claveActual, $clavePeajeSAT, true) ||
                        str_contains($descripcion, 'PEAJE') ||
                        str_contains($descripcion, 'TUNEL') ||
                        str_contains($descripcion, 'CASETAS') ||
                        str_contains($descripcion, 'AUTOBUS')
                    ) {
                        $esPeaje = true;
                        break;
                    }
                }

                if (!$esPeaje && $importeTotalComision > 0 && $totalFacturaFloat > $saldoDisponible) {
                    return response()->json([
                        'status' => 'error',
                        'filename' => $filename,
                        'message' => "El importe total de la factura ($" . number_format($totalFacturaFloat, 2) . ") excede el saldo disponible."
                    ], 403);
                }


                $verif = $this->sat->validate($rfcEmisor, $rfcReceptor, $totalFactura, $uuid);
                $statusSat = $verif['estado'] ?? 'ERROR';

                if (in_array($verif['estado'], ['Cancelado', 'No Encontrado'], true)) {
                    return response()->json([
                        'status' => 'error',
                        'filename' => $filename,
                        'message' => "SAT: CFDI {$verif['estado']} (Código: {$verif['codigoEstatus']})"
                    ], 409);
                }

                if ($verif['estado'] === 'ERROR') {
                    return response()->json([
                        'status' => 'warning',
                        'filename' => $filename,
                        'message' => "SAT no disponible: {$verif['mensaje']}"
                    ], 503);
                }

                if (!$verif['ok']) {
                    return response()->json([
                        'status' => 'error',
                        'filename' => $filename,
                        'message' => "SAT: {$verif['mensaje']} (Estado: {$verif['estado']}, Código: {$verif['codigoEstatus']})"
                            . ($verif['esCancelable'] ? " | EsCancelable: {$verif['esCancelable']}" : '')
                            . ($verif['estatusCancelacion'] ? " | EstatusCancelación: {$verif['estatusCancelacion']}" : ''),
                    ], 409);
                }

                $existingFactura = Factura::where('cfdi', $uuid)->first();
                if ($existingFactura) {
                    return response()->json([
                        'status' => 'error',
                        'filename' => $filename,
                        'message' => 'Error al guardar la factura: Ya existe una factura con el UUID ' . $uuid . '. Si desea asociar un PDF, súbalo por separado.'
                    ], 409);
                }

                if ($xmlDate->between($commissionStartDate, $commissionEndDate) && empty($idFacturaReemplazada) || $comision->status == 1) {
                    $uniqueFileName = $uuid . '.xml';
                    Storage::disk('facturas')->put($uniqueFileName, file_get_contents($file->getRealPath()));

                    try {
                        $retenIva = 0;
                        if (isset($extractedData['impuestos']['retenciones'])) {
                            foreach ($extractedData['impuestos']['retenciones'] as $retencion) {
                                if (($retencion['impuesto'] ?? '') === '002') {
                                    $retenIva += (float) ($retencion['importe'] ?? 0);
                                }
                            }
                        }

                        $ivaTrasladado = 0;
                        $iepsTrasladado = 0;
                        if (isset($extractedData['impuestos']['traslados'])) {
                            foreach ($extractedData['impuestos']['traslados'] as $traslado) {
                                if (($traslado['impuesto'] ?? '') === '002') {
                                    $ivaTrasladado += (float) ($traslado['importe'] ?? 0);
                                } elseif (($traslado['impuesto'] ?? '') === '003') {
                                    $iepsTrasladado += (float) ($traslado['importe'] ?? 0);
                                }
                            }
                        }

                        $descripcionConceptos = '';
                        if (!empty($extractedData['conceptos'])) {
                            $descripciones = array_column($extractedData['conceptos'], 'descripcion');
                            $descripcionConceptos = implode(' | ', array_filter($descripciones));
                            if (strlen($descripcionConceptos) > 255) {
                                $descripcionConceptos = substr($descripcionConceptos, 0, 252) . '...';
                            }
                        }
                        $tasasIva = array_column($extractedData['impuestos']['iva'] ?? [], 'tasa');

                        $tasaMax = !empty($tasasIva) ? max(array_map('floatval', $tasasIva)) : 0.0;

                        Factura::create([
                            'cfdi'            => $extractedData['timbreFiscalDigital']['uuid'],
                            'importe'         => (float)$extractedData['comprobante']['total'],
                            'idcomision'      => $idComision,
                            'rfcEmisor'       => $extractedData['emisor']['rfc'],
                            'rfcReceptor'     => $extractedData['receptor']['rfc'],
                            'dateFactura'     => $xmlDate->format('Y-m-d'),
                            'subtotal'        => (float)$extractedData['comprobante']['subtotalNeto'],

                            'iva'      => (float)(array_slice($extractedData['impuestos']['iva'] ?? [], -1, 1)[0]['importe'] ?? 0),
                            'ish'      => (float)(array_slice($extractedData['impuestos']['ish'] ?? [], -1, 1)[0]['importe'] ?? 0),
                            'ieps'     => (float)(array_slice($extractedData['impuestos']['ieps'] ?? [], -1, 1)[0]['importe'] ?? 0),
                            'isr'      => (float)(array_slice($extractedData['impuestos']['isr'] ?? [], -1, 1)[0]['importe'] ?? 0),
                            'retenIva' => (float)(array_slice($extractedData['impuestos']['retenciones'] ?? [], -1, 1)[0]['importe'] ?? 0),
                            'retenIsh'        => 0,
                            'tasa'            => $tasaMax,

                            'propina'         => 0,
                            'regimenReceptor' => $extractedData['receptor']['regimenFiscalReceptor'],
                            'regimenEmisor'   => $extractedData['emisor']['regimenFiscal'],
                            'formaPago'       => $extractedData['comprobante']['formaPago'],
                            'cp'              => $extractedData['receptor']['domicilioFiscalReceptor'],
                            'descripcion'     => $descripcionConceptos,

                            'status'          => $statusSat,
                            'id_user'         => Auth::user()->id_user,
                            'pdf_path'        => null,
                            'id_factura_reemplazo' => $idFacturaReemplazada,
                            'cancelacion'     => '0',
                            'idtypefactura'   => null,
                            'evidenciarequerida' => 0,
                            'observaciones'   => null,

                            'impuestos_locales' => json_encode($extractedData['impuestos']['ish']),
                            'otros'           => json_encode($extractedData['impuestos']['otros'])
                        ]);
                    } catch (\Illuminate\Database\QueryException $dbException) {
                        Storage::disk('facturas')->delete($uniqueFileName);
                        return response()->json([
                            'status' => 'error',
                            'filename' => $filename,
                            'message' => 'Error al guardar la factura en la base de datos: ' . $dbException->getMessage()
                        ], 500);
                    } catch (\Exception $e) {
                        Storage::disk('facturas')->delete($uniqueFileName);
                        return response()->json([
                            'status' => 'error',
                            'filename' => $filename,
                            'message' => 'Error inesperado al procesar y guardar la factura: ' . $e->getMessage()
                        ], 500);
                    }

                    $rfcEmisor = $extractedData['emisor']['rfc'] ?? '';
                    $nombreEmisor = $extractedData['emisor']['nombre'] ?? '';
                    $serieFolio = ($extractedData['comprobante']['serie'] ?? '') . (!empty($extractedData['comprobante']['serie']) && !empty($extractedData['comprobante']['folio']) ? '-' : '') . ($extractedData['comprobante']['folio'] ?? '');
                    if (empty($serieFolio)) {
                        $serieFolio = 'N/A';
                    }
                    $regimenEmisorDisplay = $extractedData['emisor']['regimenFiscal'] ?? '';

                    $successMessage = "La fecha del XML (" . $xmlDate->format('Y-m-d') . ") está **DENTRO** del rango de comisión. ";
                    if ($importeTotalComision > 0) {
                        $successMessage .= "Cumple con el límite de importe (**$" . number_format($importeTotalComision, 2) . "**). ";
                    }
                    $successMessage .= "Emisor: **" . ($nombreEmisor ?: 'Desconocido') . "**, ";
                    $successMessage .= "RFC Emisor: **" . ($rfcEmisor ?: 'Desconocido') . "**, ";
                    $successMessage .= "Total: **$" . number_format($totalFacturaFloat, 2, '.', ',') . "**, ";
                    $successMessage .= "UUID: **" . ($uuid ?: 'Desconocido') . "**, ";
                    $successMessage .= "Serie/Folio: **" . $serieFolio . "**. ";
                    $successMessage .= "Régimen Fiscal: **" . ($this->mapRegimenFiscal($regimenEmisorDisplay) ?: 'Desconocido') . "**. ";
                    $successMessage .= "Factura guardada correctamente.";

                    return response()->json([
                        'status' => 'success',
                        'filename' => $filename,
                        'message' => $successMessage,
                        'invoice_amount' => $totalFacturaFloat
                    ]);
                } else {
                    return response()->json([
                        'status' => 'warning',
                        'filename' => $filename,
                        'message' => 'La fecha del XML (' . $xmlDate->format('Y-m-d') . ') está FUERA del rango de comisión. Archivo NO guardado.',
                        'invoice_amount' => 0
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'filename' => $filename,
                    'message' => 'Error al procesar el archivo XML: ' . $e->getMessage()
                ], 500);
            }
        } elseif ($extension === 'pdf') {
            // ****
            // $uuid = null;
            // $filename = $file->getClientOriginalName();

            // try {
            //     $parser = new \Smalot\PdfParser\Parser();
            //     $pdf = $parser->parseFile($file->getRealPath());
            //     $pdfText = $pdf->getText();
            //     $lines = explode("\n", $pdfText);

            //     foreach ($lines as $line) {
            //         if (strpos($line, '||1.1|') !== false) {
            //             $lineClean = str_replace(['‐', '–', '—'], '-', trim($line));
            //             $parts = explode('|', $lineClean);
            //             if (isset($parts[3]) && strlen($parts[3]) === 36) {
            //                 $uuid = strtoupper($parts[3]);
            //                 Log::info("UUID obtenido de Cadena Original: " . $uuid);
            //                 break;
            //             }
            //         }
            //     }

            //     if (!$uuid) {
            //         Log::info("Texto digital fallido. Intentando por nombre de archivo: " . $filename);
            //         if (preg_match('/([A-F0-9]{8}-[A-F0-9]{4}-[A-F0-9]{4}-[A-F0-9]{4}-[A-F0-9]{12})/i', $filename, $matches)) {
            //             $uuid = strtoupper($matches[1]);
            //         } elseif (preg_match('/_([A-F0-9]{7,12})\.pdf/i', $filename, $matches)) {
            //             $suffix = strtoupper($matches[1]);
            //             $f = \App\Models\Factura::where('cfdi', 'LIKE', "%$suffix")->first();
            //             $uuid = $f ? $f->cfdi : null;
            //         }
            //     }

            //     if (!$uuid && method_exists($this, 'procesarConTextract')) {
            //         try {
            //             Log::info("Intentando AWS Textract...");
            //             $res = $this->procesarConTextract($file->getRealPath());
            //             $uuid = $res['uuid'] ?? null;
            //         } catch (\Exception $e) {
            //             // Si AWS falla por suscripción o red, ignoramos el error y uuid sigue siendo null
            //             Log::error("AWS no disponible: " . $e->getMessage());
            //             $uuid = null;
            //         }
            //     }

            //     if (!$uuid) {
            //         return response()->json(['status' => 'error', 'message' => 'No se detectó UUID por ningún medio.'], 404);
            //     }

            //     $factura = \App\Models\Factura::where('cfdi', $uuid)->first();

            //     if ($factura) {
            //         Storage::disk('facturas')->put($uuid . '.pdf', file_get_contents($file->getRealPath()));
            //         $factura->update(['pdf_path' => $uuid . '.pdf']);

            //         return response()->json(['status' => 'success', 'message' => "Asociado: $uuid"]);
            //     }

            //     return response()->json(['status' => 'error', 'message' => "UUID $uuid no registrado."], 404);
            // } catch (\Exception $e) {
            //     Log::error("Error crítico: " . $e->getMessage());
            //     return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            // }
            // ****
            $uuid = null;
            $totalFactura = 0;
            $pdfDate = null;
            $rfcEmisor = null;
            $rfcReceptor = null;
            $descripcionConceptos = 'PDF asociado a CFDI existente';

            try {
                $parser = new Parser();
                $pdf = $parser->parseFile($file->getRealPath());

                $pdfText = $pdf->getText();

                // Log::info("Contenido extraído del PDF ({$filename}):");
                // Log::info($pdfText);

                $lines = explode("\n", $pdfText);

                foreach ($lines as $line) {
                    if (strpos($line, '||1.1|') !== false) {
                        $lineClean = str_replace(['‐', '–', '—'], '-', trim($line));
                        $parts = explode('|', $lineClean);
                        if (isset($parts[3]) && strlen($parts[3]) === 36) {
                            $uuid = strtoupper($parts[3]);
                            Log::info("UUID obtenido de Cadena Original: " . $uuid);
                            break;
                        }
                    }
                }
                Log::info($lines);

                $uuidPattern = '/[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}/i';
                if (preg_match($uuidPattern, $pdfText, $matches)) {
                    $uuid = strtoupper($matches[0]);
                }

                if (empty($uuid)) {
                    return response()->json([
                        'status' => 'error',
                        'filename' => $filename,
                        'message' => 'No se pudo extraer el UUID del PDF. No se puede asociar sin un UUID válido.'
                    ], 400);
                }

                $existingFactura = Factura::where('cfdi', $uuid)->first();

                if (!$existingFactura) {
                    return response()->json([
                        'status' => 'error',
                        'filename' => $filename,
                        'message' => 'No se encontró un XML con el UUID ' . $uuid . ' en la base de datos. Por favor, suba el archivo XML primero.'
                    ], 404);
                }

                if ($existingFactura->idcomision != $idComision) {
                    return response()->json([
                        'status' => 'error',
                        'filename' => $filename,
                        'message' => 'El UUID ' . $uuid . ' ya está asociado a otra comisión. No se puede asociar este PDF a la comisión actual.'
                    ], 409);
                }

                if (!empty($existingFactura->pdf_path)) {
                    return response()->json([
                        'status' => 'error',
                        'filename' => $filename,
                        'message' => 'Ya existe un PDF asociado a la factura con UUID ' . $uuid . '.'
                    ], 409);
                }

                $uniqueFileName = $uuid . '.pdf';
                Storage::disk('facturas')->put($uniqueFileName, file_get_contents($file->getRealPath()));

                try {
                    $existingFactura->pdf_path = $uniqueFileName;
                    $existingFactura->save();
                } catch (\Illuminate\Database\QueryException $dbException) {
                    Storage::disk('facturas')->delete($uniqueFileName);
                    return response()->json([
                        'status' => 'error',
                        'filename' => $filename,
                        'message' => 'Error al actualizar la factura con la ruta del PDF: ' . $dbException->getMessage()
                    ], 500);
                } catch (\Exception $e) {
                    Storage::disk('facturas')->delete($uniqueFileName);
                    return response()->json([
                        'status' => 'error',
                        'filename' => $filename,
                        'message' => 'Error inesperado al asociar el PDF a la factura: ' . $e->getMessage()
                    ], 500);
                }

                $successMessage = "PDF asociado correctamente a la factura con UUID: **" . ($uuid ?: 'Desconocido') . "**. ";
                $successMessage .= "La factura original (XML) ya estaba guardada y dentro del rango de comisión. ";
                $successMessage .= "Archivo PDF guardado como **" . $uniqueFileName . "**.";

                return response()->json([
                    'status' => 'success',
                    'filename' => $filename,
                    'message' => $successMessage,
                    'invoice_amount' => 0
                ]);
            } catch (\Smalot\PdfParser\Exception\BinaryNotFoundException $e) {
                return response()->json([
                    'status' => 'error',
                    'filename' => $filename,
                    'message' => 'Error al procesar el PDF: La herramienta "pdftotext" no se encontró. Asegúrese de que esté instalada en su servidor (ej. `sudo apt-get install poppler-utils`).'
                ], 500);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'filename' => $filename,
                    'message' => 'Error al procesar el archivo PDF: ' . $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'filename' => $filename,
                'message' => 'Tipo de archivo no soportado.'
            ], 415);
        }
    }
    private function procesarConTextract($filePath)
    {
        $textract = new \Aws\Textract\TextractClient([
            'version'     => 'latest',
            'region'      => env('AWS_DEFAULT_REGION', 'us-east-2'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $fileBytes = file_get_contents($filePath);
        $result = $textract->analyzeDocument([
            'Document' => ['Bytes' => $fileBytes],
            'FeatureTypes' => ['FORMS'],
        ]);

        $fullText = "";
        foreach ($result['Blocks'] as $block) {
            if ($block['BlockType'] == 'LINE') {
                $fullText .= $block['Text'] . " ";
            }
        }

        $uuid = null;
        if (preg_match('/[A-F0-9]{8}-[A-F0-9]{4}-[A-F0-9]{4}-[A-F0-9]{4}-[A-F0-9]{12}/i', $fullText, $matches)) {
            $uuid = strtoupper($matches[0]);
        }

        return ['uuid' => $uuid];
    }
    private function findXmlValue(SimpleXMLElement $xml, array $paths): ?string
    {
        foreach ($paths as $path) {
            $nodes = $xml->xpath($path);
            if (!empty($nodes)) {
                return (string) $nodes[0];
            }
        }
        return null;
    }

    private function extractAllCfdiData(SimpleXMLElement $xml): array
    {
        $xml->registerXPathNamespace('cfdi', 'http://www.sat.gob.mx/cfd/4');
        $xml->registerXPathNamespace('tfd', 'http://www.sat.gob.mx/TimbreFiscalDigital');
        $xml->registerXPathNamespace('implocal', 'http://www.sat.gob.mx/implocal');

        $emisor = $xml->xpath('//cfdi:Emisor')[0] ?? null;
        $receptor = $xml->xpath('//cfdi:Receptor')[0] ?? null;
        $tfd = $xml->xpath('//tfd:TimbreFiscalDigital')[0] ?? null;

        $subTotalBruto = (float)($xml['SubTotal'] ?? 0);
        $descuentoTotal = (float)($xml['Descuento'] ?? 0);
        $total = (float)($xml['Total'] ?? 0);

        $data = [
            'comprobante' => [
                'total' => $total,
                'subtotal' => $subTotalBruto,
                'subtotalNeto' => $subTotalBruto - $descuentoTotal,
                'formaPago' => (string)($xml['FormaPago'] ?? ''),
                'cp' => (string)($xml['LugarExpedicion'] ?? ''), // CP emisor
                'serieFolio' => ((string)($xml['Serie'] ?? '') . (string)($xml['Folio'] ?? ''))
            ],
            'emisor' => [
                'rfc' => (string)($emisor['Rfc'] ?? ''),
                'regimenFiscal' => (string)($emisor['RegimenFiscal'] ?? ''),
            ],
            'receptor' => [
                'rfc' => (string)($receptor['Rfc'] ?? ''),
                'regimenFiscalReceptor' => (string)($receptor['RegimenFiscalReceptor'] ?? ''),
                'domicilioFiscalReceptor' => (string)($receptor['DomicilioFiscalReceptor'] ?? ''),
            ],
            'timbreFiscalDigital' => [
                'uuid' => (string)($tfd['UUID'] ?? ''),
            ],
            'impuestos' => [
                'iva' => [],
                'isr' => [],
                'ieps' => [],
                'ish' => [],
                'retenciones' => [],
                'otros' => []
            ],
            'conceptos' => []
        ];

        foreach ($xml->xpath('//cfdi:Concepto') as $c) {
            $data['conceptos'][] = ['descripcion' => (string)$c['Descripcion']];
        }

        foreach ($xml->xpath('//cfdi:Impuestos/cfdi:Traslados/cfdi:Traslado') as $t) {
            $tipo = (string)$t['Impuesto'];
            // Aquí ya se está guardando la tasa
            $detalle = [
                'importe' => (float)$t['Importe'],
                'tasa'    => (string)$t['TasaOCuota']
            ];

            if ($tipo === '002') $data['impuestos']['iva'][] = $detalle;
            elseif ($tipo === '003') $data['impuestos']['ieps'][] = $detalle;
            else $data['impuestos']['otros'][] = ['code' => $tipo, 'detalle' => $detalle];
        }

        foreach ($xml->xpath('//cfdi:Impuestos/cfdi:Retenciones/cfdi:Retencion') as $r) {
            $tipo = (string)$r['Impuesto'];
            $impData = ['importe' => (float)$r['Importe']];
            if ($tipo === '001') $data['impuestos']['isr'][] = $impData;
            elseif ($tipo === '002') $data['impuestos']['retenciones'][] = $impData; // Retencion IVA
        }

        foreach ($xml->xpath('//implocal:ImpuestosLocales/implocal:TrasladosLocales') as $local) {
            $data['impuestos']['ish'][] = ['importe' => (float)$local['Importe']];
        }
        Log::debug('Datos extraídos de CFDI:', $data);

        return $data;
    }
    private function mapRegimenFiscal(string $code): ?string
    {
        $regimenes = [
            '601' => 'General de Ley Personas Morales',
            '603' => 'Personas Morales con Fines no Lucrativos',
            '605' => 'Sueldos y Salarios e Ingresos Asimilados a Salarios',
            '606' => 'Arrendamiento',
            '607' => 'Régimen de Enajenación o Adquisición de Bienes',
            '608' => 'Demas Ingresos',
            '609' => 'Consolidación',
            '610' => 'Residentes en el Extranjero sin Establecimiento Permanente en México',
            '611' => 'Ingresos por Dividendos (socios y accionistas)',
            '612' => 'Personas Físicas con Actividades Empresariales y Profesionales',
            '614' => 'Ingresos por Intereses',
            '616' => 'Sin Obligaciones Fiscales',
            '620' => 'Sociedades Cooperativas de Producción que optan por diferir sus ingresos',
            '621' => 'Incorporación Fiscal',
            '622' => 'Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras',
            '623' => 'Opcional para Grupos de Sociedades',
            '624' => 'Coordinados',
            '625' => 'Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas',
            '626' => 'Régimen Simplificado de Confianza',
        ];

        return $regimenes[$code] ?? null;
    }

    public function facturas_list_json(Request $request): JsonResponse
    {
        $request->validate([
            'idcomision' => 'required|integer',
        ]);

        $idComision = $request->input('idcomision');

        $query = DB::connection('oracle_primary')->table('facturas')
            ->select(
                'facturas.idfactura',
                'facturas.idcomision',
                'facturas.cfdi',
                'facturas.rfcemisor as rfc_emisor',
                'facturas.rfcreceptor as rfc_receptor',
                'facturas.importe',
                'facturas.propina',
                'facturas.iva',
                'facturas.ish',
                'facturas.ieps',
                'facturas.reteniva',
                'facturas.retenish',
                'facturas.datefactura',
                'facturas.subtotal',
                'facturas.STATUS',
                'facturas.regimenreceptor',
                'facturas.regimenemisor',
                'facturas.formapago',
                'facturas.cancelacion',
                'facturas.observaciones',
                'facturas.id_factura_reemplazo',
                'facturas.cp',
                'facturas.descripcion',
                'facturas.pdf_path',
                'facturas.idtypefactura as tipo_gasto',
                'comisiones.idstatuscomision',
                'typefactura.typefactura',
                DB::raw("(SELECT LISTAGG(name, ',') WITHIN GROUP (ORDER BY name)
                         FROM files WHERE files.idfactura = facturas.idfactura) as nombres_archivos"),
                DB::raw("users.nomusuario || ' ' || users.appusuario || ' ' || users.apmusuario as nombre_completo")
            )
            ->join('comisiones', 'facturas.idcomision', '=', 'comisiones.idcomision')
            ->leftjoin('typefactura', 'typefactura.idtypefactura', '=', 'facturas.idtypefactura')
            ->join('users', 'facturas.id_user', '=', 'users.id_user')
            ->where('facturas.idcomision', $idComision)
            ->orderBy('facturas.idfactura', 'desc');

        $jsonData = DataTables::of($query)
            ->filterColumn('nombre_completo', function ($query, $keyword) {
                $query->whereRaw("upper(users.nomusuario || ' ' || users.appusuario || ' ' || users.apmusuario) LIKE ?", ["%" . strtoupper($keyword) . "%"]);
            })
            ->addColumn('btn_f', 'buttons.button_file')
            ->rawColumns(['btn_f'])
            ->toJson();

        return $jsonData;
    }

    public function facturas_reemplazo_list_json(Request $request): JsonResponse
    {
        // Validamos que el ID de la factura principal sea obligatorio y numérico
        $request->validate([
            'idFactura' => 'required|integer',
        ]);

        $idFacturaPrincipal = $request->input('idFactura');

        $query = DB::connection('oracle_primary')->table('facturas')
            ->select(
                'facturas.idfactura',
                'facturas.idcomision',
                'facturas.cfdi',
                'facturas.rfcemisor as rfc_emisor',
                'facturas.rfcreceptor as rfc_receptor',
                'facturas.importe',
                'facturas.propina',
                'facturas.iva',
                'facturas.ish',
                'facturas.ieps',
                'facturas.reteniva',
                'facturas.retenish',
                'facturas.datefactura',
                'facturas.subtotal',
                'facturas.STATUS',
                'facturas.regimenreceptor',
                'facturas.regimenemisor',
                'facturas.formapago',
                'facturas.cancelacion',
                'facturas.observaciones',
                'facturas.id_factura_reemplazo',
                'facturas.cp',
                'facturas.descripcion',
                'facturas.pdf_path',
                'facturas.idtypefactura as tipo_gasto',
                'comisiones.idstatuscomision',
                'typefactura.typefactura',
                DB::raw("(SELECT LISTAGG(name, ',') WITHIN GROUP (ORDER BY name)
                      FROM files WHERE files.idfactura = facturas.idfactura) as nombres_archivos"),
                DB::raw("users.nomusuario || ' ' || users.appusuario || ' ' || users.apmusuario as nombre_completo")
            )
            ->join('comisiones', 'facturas.idcomision', '=', 'comisiones.idcomision')
            ->leftjoin('typefactura', 'typefactura.idtypefactura', '=', 'facturas.idtypefactura')
            ->join('users', 'facturas.id_user', '=', 'users.id_user')
            ->where('facturas.id_factura_reemplazo', $idFacturaPrincipal)
            ->orderBy('facturas.idfactura', 'desc');

        $jsonData = DataTables::of($query)
            ->filterColumn('nombre_completo', function ($query, $keyword) {
                $query->whereRaw("upper(users.nomusuario || ' ' || users.appusuario || ' ' || users.apmusuario) LIKE ?", ["%" . strtoupper($keyword) . "%"]);
            })
            ->addColumn('btn_f', 'buttons.button_file')
            ->rawColumns(['btn_f'])
            ->toJson();

        return $jsonData;
    }

    public function stream($file)
    {
        return response()->file(storage_path('app/' . 'facturas/' . $file));
    }

    public function comprobacionExcel()
    {
        $statusComision = DB::connection('oracle_primary')->table('statuscomision')->get();
        $modalidadComision = DB::connection('oracle_primary')->table('modalidades')->get();
        $typeComision = DB::connection('oracle_primary')->table('typecomision')->get();
        $destinos = DB::connection('oracle_primary')->table('destinos')->get();
        $users = DB::connection('oracle_primary')->table('users')->where('idtype', '=', 1)->get();
        $objetives = DB::connection('oracle_primary')->table('objetives')->get();
        $direcciones = DB::connection('oracle_primary')->table('direcciones')->get();
        return view('admin.comprobacionExcel', compact(['statusComision', 'modalidadComision', 'typeComision', 'destinos', 'users', 'objetives', 'direcciones']));
    }

    public function actualizarXml(Request $request, $idfactura)
    {
        $factura = Factura::findOrFail($idfactura);

        if ($factura->status !== 'Cancelado') {
            return response()->json([
                'status' => 'error',
                'message' => 'Solo se puede actualizar el XML de una factura que ha sido cancelada.'
            ], 403);
        }

        if ($factura->xml_actualizado === 'SI') {
            return response()->json([
                'status' => 'error',
                'message' => 'Esta factura ya ha sido actualizada por una ocasión.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'new_xml_file' => 'required|mimes:xml|max:10240',
        ], [
            'new_xml_file.required' => 'Debe seleccionar un nuevo archivo XML.',
            'new_xml_file.mimes' => 'El archivo debe ser un XML válido.',
            'new_xml_file.max' => 'El archivo no debe exceder los 10MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $file = $request->file('new_xml_file');
        $filename = $file->getClientOriginalName();

        try {
            $xmlContent = file_get_contents($file->getRealPath());
            $xml = new SimpleXMLElement($xmlContent);

            $xml->registerXPathNamespace('cfdi', 'http://www.sat.gob.mx/cfd/3');
            $xml->registerXPathNamespace('tfd', 'http://www.sat.gob.mx/TimbreFiscalDigital');

            $extractedData = $this->extractAllCfdiData($xml);
            $newUuid = strtoupper($extractedData['timbreFiscalDigital']['uuid'] ?? '');

            $existingFacturaWithNewUuid = Factura::where('cfdi', $newUuid)->first();
            if ($existingFacturaWithNewUuid && $existingFacturaWithNewUuid->idfactura !== $factura->idfactura) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'El nuevo XML ya existe en la base de datos con otro ID de factura.'
                ], 409);
            }

            $uniqueFileName = $newUuid . '.xml';
            Storage::disk('facturas')->put($uniqueFileName, file_get_contents($file->getRealPath()));

            $factura->cfdi = $newUuid;
            $factura->importe = (float)($extractedData['comprobante']['total'] ?? 0);
            $factura->rfcemisor = $extractedData['emisor']['rfc'] ?? null;
            $factura->rfcreceptor = $extractedData['receptor']['rfc'] ?? null;
            $factura->datefactura = Carbon::parse($this->findXmlValue($xml, ['//cfdi:Comprobante/@Fecha']))->format('Y-m-d');
            $factura->subtotal = (float)($extractedData['comprobante']['subTotal'] ?? 0);
            $factura->regimenreceptor = $extractedData['receptor']['regimenFiscalReceptor'] ?? null;
            $factura->regimenemisor = $extractedData['emisor']['regimenFiscal'] ?? null;
            $factura->formapago = $extractedData['comprobante']['formaPago'] ?? null;
            $factura->cp = $extractedData['receptor']['domicilioFiscalReceptor'] ?? null;
            $factura->descripcion = $this->getDescripcionConceptos($extractedData);
            $factura->iva = $this->getIvaTrasladado($extractedData);
            $factura->ieps = $this->getIepsTrasladado($extractedData);
            $factura->reteniva = $this->getRetenIva($extractedData);

            $factura->status = 'Vigente';
            $factura->cancelacion = 'NO';
            $factura->xml_actualizado = 'SI';
            $factura->save();

            return response()->json([
                'status' => 'success',
                'message' => "La factura con UUID {$newUuid} ha sido actualizada correctamente.",
                'invoice_amount' => (float) $factura->importe
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al procesar el nuevo archivo XML: ' . $e->getMessage()
            ], 500);
        }
    }

    public function anularFactura(Request $request)
    {
        $idfactura = $request->get('idfactura');
        $estado = $request->get('action');
        $observaciones = $request->get('observaciones');

        $datosComision = DB::connection('oracle_primary')->table('facturas')
            ->join('comisiones', 'facturas.idcomision', '=', 'comisiones.idcomision')
            ->join('modalidades', 'comisiones.idmodalidad', '=', 'modalidades.idmodalidad')
            ->where('facturas.idfactura', $idfactura)
            ->select('modalidades.acronym', 'comisiones.comision', 'comisiones.idcomision', 'comisiones.yearcomision', 'facturas.cfdi as folio_factura')
            ->first();

        $viatico = $datosComision ? "{$datosComision->acronym}_{$datosComision->comision}_{$datosComision->yearcomision}" : "ID Factura: $idfactura";

        if ($estado == 'anular') {
            DB::connection('oracle_primary')->table('facturas')
                ->where('idfactura', '=', $idfactura)
                ->update([
                    'observaciones' => strtoupper($observaciones),
                    'cancelacion' => '1',
                    'evidenciarequerida' => $request->get('evidenciaRequerida'),
                ]);

            $accionLog = "Ha ANULADO la factura ({$datosComision->folio_factura}) del viático: $viatico";
        }
        if ($estado == 'aceptar') {
            DB::connection('oracle_primary')->table('facturas')
                ->where('idfactura', '=', $idfactura)
                ->update([
                    'cancelacion' => '2'
                ]);

            $accionLog = "Ha VALIDADO la factura ({$datosComision->folio_factura}) del viático: $viatico";
        }
        if ($estado == 'rechazar') {
            DB::connection('oracle_primary')->table('facturas')
                ->where('idfactura', '=', $idfactura)
                ->update([
                    'cancelacion' => '3'
                ]);

            $accionLog = "Ha asignado el valor NO CORRESPONDE A LA COMISIÓN a la factura ({$datosComision->folio_factura}) del viático: $viatico";
        }

        $arrayid = (array) $datosComision->idcomision;
        DB::table('registros')->insert([
            'movimiento' => $accionLog,
            'id_user' => Auth::user()->id_user,
            'fecha_movimiento' => now(),
            'created_at' => date('Y-m-d-H:i:s'),
            'idcomision' => json_encode($arrayid)
        ]);

        return redirect()->back();
    }

    public function comprobacionAdicional($idFactura)
    {
        $factura = Factura::where('idfactura', $idFactura)->firstOrFail();

        $idComision = $factura->idcomision ?? $factura->comision;

        $query = Comision::where('idcomision', $idComision)->firstOrFail();

        $facturasAcumuladas = Factura::where('idcomision', $idComision)
            ->where('cancelacion', '!=', '1')
            ->where('idtypefactura', '!=', '7')
            ->sum('importe');

        $gastosSinComprobanteTotal = GastosSinComprobante::where('idcomision', $idComision)
            ->where('activo', 1)
            ->sum('monto');

        $importeTotalComprobado = $facturasAcumuladas + $gastosSinComprobanteTotal;

        return view('comprobaciones.comprobacionAdicional', compact(
            'factura',
            'idComision',
            'query',
            'facturasAcumuladas',
            'idFactura',
            'importeTotalComprobado',
            'gastosSinComprobanteTotal'
        ));
    }

    public function saveTickets(Request $request)
    {
        $request->validate([
            'idFactura' => 'required|numeric',
            'files' => 'required|array',
            'files.*' => 'file|max:5120',
        ]);

        $facturaId = $request->get('idFactura');
        $idComision = $request->get('idComision');

        $uploadedFiles = $request->file('files');
        $savedFiles = [];

        foreach ($uploadedFiles as $file) {
            try {
                $fileName = time() . '_' . $file->getClientOriginalName();


                Storage::disk('tickets')->put($fileName, file_get_contents($file->getRealPath()));
                $fileRecord = Files::create([
                    'name' => $fileName,
                    'idFactura' => $facturaId,
                ]);

                $savedFiles[] = [
                    'name' => $fileRecord->name,
                    'id' => $fileRecord->idFile,
                ];
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Ocurrió un error al subir un archivo.');
            }
        }

        return redirect('comprobacionForm/' . $idComision);
    }

    public function downloadAditional($name_file)
    {
        try {
            $name = Crypt::decryptString($name_file);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'El nombre del archivo no es válido.');
        }

        $path = storage_path("app/tickets/$name");

        if (!File::exists($path)) {
            return redirect()->back()->with('warning', 'No es posible descargar el archivo. Compruebe el nombre del archivo o verifique si se guardó correctamente.');
        }

        return response()->file($path);
    }
    public function updateRevision($idComisionHasheado)
    {
        try {
            $idComision = Crypt::decryptString($idComisionHasheado);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'El identificador de la comisión es inválido o ha sido alterado.');
        }

        $hasUnclassifiedInvoices = DB::connection('oracle_primary')
            ->table('facturas')
            ->where('idcomision', '=', $idComision)
            ->where('cancelacion', '!=', '1')
            ->whereNull('idtypefactura')
            ->exists();

        if ($hasUnclassifiedInvoices) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede finalizar la comprobación: Existen facturas sin clasificar.'
            ], 422);
        }

        $comision = DB::connection('oracle_primary')
            ->table('comisiones')
            ->select('idmodalidad', 'importe')
            ->where('idcomision', '=', $idComision)
            ->first();

        if (!$comision) {
            return response()->json(['success' => false, 'message' => 'Comisión no encontrada.'], 404);
        }

        $newStatus = ($comision->idmodalidad == 1) ? 12 : 21;

        DB::connection('oracle_primary')->beginTransaction();

        try {
            DB::connection('oracle_primary')
                ->table('comisiones')
                ->where('idcomision', '=', $idComision)
                ->update([
                    'idstatuscomision' => $newStatus,
                ]);

            DB::connection('oracle_primary')->commit();
            $arrayid = (array) $idComision;
            DB::table('registros')->insert([
                'movimiento' => "HA ENVIADO UNA REVISIÓN DE LA COMISIÓN:",
                'id_user' => Auth::user()->id_user,
                'fecha_movimiento' => now(),
                'created_at' => date('Y-m-d-H:i:s'),
                'idcomision' => json_encode($arrayid),
                'idstatus' => $newStatus
            ]);
            return response()->json([
                'success' => true,
                'redirect_url' => url('detallesComision/' . $idComisionHasheado)
            ]);
        } catch (\Exception $e) {
            DB::connection('oracle_primary')->rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la revisión: ' . $e->getMessage()
            ], 500);
        }
    }
    public function clasificarFactura(Request $request, $idfactura)
    {
        $request->validate([
            'tipo_gasto' => 'nullable|string|max:50',
        ]);

        try {
            $factura = Factura::findOrFail($idfactura);

            $factura->idtypefactura = $request->input('tipo_gasto');
            $factura->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Tipo de gasto actualizado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo clasificar la factura: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateComprobacion($idComision)
    {
        $infoModalidad = Comision::where('idcomision', $idComision)->first();

        $infoModalidad = Comision::find($idComision);

        if ($infoModalidad->idmodalidad == 1) {
            $statusComprobacion = 10;
        } else {
            $statusComprobacion = 20;
        }
        DB::connection('oracle_primary')->table('comisiones')
            ->where('idcomision', '=', $idComision)
            ->update([
                'idstatuscomision' => $statusComprobacion
            ]);
        $arrayid = (array) $idComision;
        DB::table('registros')->insert([
            'movimiento' => "HA GUARDADO EL AVANCE DE LA COMPROBACIÓN DE LA COMISIÓN:",
            'id_user' => Auth::user()->id_user,
            'fecha_movimiento' => now(),
            'created_at' => date('Y-m-d-H:i:s'),
            'idcomision' => json_encode($arrayid),
            'idstatus' => $statusComprobacion
        ]);
        return redirect()->back();
    }
    public function savePays(Request $request)
    {
        $request->validate([
            'idcomision' => 'required|integer|exists:comisiones,idcomision',
            'datepay' => 'required|date',
            'conceptopago' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0.01',
            'proof_file' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $idComision = $request->idcomision;
        $filePathForDb = null;

        try {
            if ($request->hasFile('proof_file')) {
                $path = $request->file('proof_file')->store(
                    'tickets/' . $idComision,
                    'public'
                );

                if (!$path) {
                    throw new \Exception('Error al subir el archivo del comprobante.');
                }

                $filePathForDb = $path;
            }

            GastosSinComprobante::create([
                'idcomision' => $idComision,
                'datepay' => $request->datepay,
                'conceptopago' => $request->conceptopago,
                'monto' => $request->monto,
                'proof_file' => $filePathForDb,
                'activo' => '1',
            ]);

            return redirect()->back()->with('success', 'Comprobante simple cargado exitosamente.');
        } catch (\Exception $e) {
            if ($filePathForDb) {
                Storage::disk('public')->delete($filePathForDb);
            }
            return back()->withErrors(['general' => 'Ocurrió un error al guardar el comprobante: ' . $e->getMessage()])->withInput();
        }
    }
    public function eliminarComprobante(Reintegro $reintegro)
    {
        DB::connection('oracle_primary')->table('reintegros')
            ->where('idreintegro', '=', $reintegro->idreintegro)
            ->update([
                'comprobante' => null,
                'iddeposito' => null
            ]);

        return back()->with('success', 'El comprobante de reintegro ha sido eliminado correctamente.');
    }

    public function deleteFactura($id)
    {
        $factura = DB::table('facturas')->where('idfactura', $id)->first();

        if ($factura) {
            if (!empty($factura->id_factura_reemplazo)) {
                return back()->with('warning', 'No se puede eliminar la factura porque tiene un reemplazo asociado.');
            }

            DB::transaction(function () use ($id) {
                DB::table('files')->where('idfactura', $id)->delete();
                DB::table('facturas')->where('idfactura', $id)->delete();
            });
            $arrayid = (array) $factura->idcomision;
            DB::table('registros')->insert([
                'movimiento' => "HA ACTUALIZADO LA REVISIÓN DE LA COMISIÓN:",
                'id_user' => Auth::user()->id_user,
                'fecha_movimiento' => now(),
                'created_at' => date('Y-m-d-H:i:s'),
                'idcomision' => json_encode($arrayid),
            ]);
            return back()->with('success', 'La factura ha sido eliminada correctamente.');
        }

        return back()->with('warning', 'No se encontró la factura.');
    }
    public function updateFactura($id)
    {
        $factura = DB::table('facturas')->where('idfactura', $id)->first();

        if ($factura) {


            DB::table('facturas')->where('idfactura', $id)->update([
                'observaciones' => null,
                'cancelacion' => 0,
            ]);
            $arrayid = (array) $factura->idcomision;
            DB::table('registros')->insert([
                'movimiento' => "HA LIMPIADO LA INFORMACIÓN DE LA FACTURA: $factura->cfdi DE LA COMISIÓN: ",
                'id_user' => Auth::user()->id_user,
                'fecha_movimiento' => now(),
                'created_at' => date('Y-m-d-H:i:s'),
                'idcomision' => json_encode($arrayid),
            ]);
            return back()->with('success', 'La factura se ha limpiado correctamente');
        }

        return back()->with('warning', 'No se encontró la factura.');
    }

    public function calcularReintegro(Request $request, $idComision)
    {
        $comision = DB::connection('oracle_primary')
            ->table('comisiones')
            ->where('idcomision', $idComision)
            ->first();

        if (!$comision) {
            return response()->json(['success' => false, 'message' => 'Comisión no encontrada'], 404);
        }

        $totalFacturas = DB::connection('oracle_primary')
            ->table('facturas')
            ->where('idcomision', '=', $idComision)
            ->where('cancelacion', '=', '2')
            ->sum('importe');

        $totalSinComprobante = DB::connection('oracle_primary')
            ->table('sincomprobantes')
            ->where('idcomision', '=', $idComision)
            ->where('activo', '=', '1')
            ->sum('monto');

        $montoReintegro = $comision->importe - $totalFacturas - $totalSinComprobante;

        DB::connection('oracle_primary')->beginTransaction();

        try {
            if ($montoReintegro != 0) {
                DB::connection('oracle_primary')
                    ->table('reintegros')
                    ->updateOrInsert(
                        [
                            'idcomision' => $idComision,
                            'idtiporeintegro' => '3'
                        ],
                        [
                            'monto' => $montoReintegro,
                        ]
                    );
            } else {
                DB::connection('oracle_primary')
                    ->table('reintegros')
                    ->where('idcomision', $idComision)
                    ->where('idtiporeintegro', '3')
                    ->delete();
            }

            DB::connection('oracle_primary')->commit();

            $textoMovimiento = $montoReintegro < 0
                ? "HA REGISTRADO UN SALDO A FAVOR DE: $" . abs($montoReintegro) . " EN LA COMISIÓN"
                : "HA AGREGADO UN REINTEGRO CON EL MONTO DE: $montoReintegro A LA COMISIÓN";

            $arrayid = (array) $comision->idcomision;
            DB::table('registros')->insert([
                'movimiento' => $textoMovimiento,
                'id_user' => Auth::user()->id_user,
                'fecha_movimiento' => now(),
                'created_at' => date('Y-m-d H:i:s'),
                'idcomision' => json_encode($arrayid),
            ]);

            return response()->json([
                'success' => true,
                'monto' => (float)$montoReintegro,
                'redirect_url' => url('detallesComision/' . $idComision)
            ]);
        } catch (\Exception $e) {
            DB::connection('oracle_primary')->rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar: ' . $e->getMessage()
            ], 500);
        }
    }
}
