<?php

namespace App\Services;

use SoapClient;
use SoapFault;

/**
 * Servicio de validación de CFDI contra el SAT (ConsultaCFDIService.svc).
 *
 * Usa la “expresión impresa” con los 4 datos oficiales:
 *   - RFC Emisor (re)
 *   - RFC Receptor (rr)
 *   - Total con 6 decimales (tt)
 *   - UUID (id)
 *
 * @link https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc?wsdl
 */
class SatCfdiValidator
{
    public function validate(string $rfcEmisor, string $rfcReceptor, $total, string $uuid): array
    {
        // Normalizacion requerida por el SAT
        $rfcEmisor   = strtoupper(trim($rfcEmisor));
        $rfcReceptor = strtoupper(trim($rfcReceptor));
        $uuid        = strtoupper(trim($uuid));

        // Total a 6 decimales, sin comas
        $totalNum = (float) str_replace(',', '', (string) $total);
        $totalFmt = number_format($totalNum, 6, '.', '');

        $expresion = "?re={$rfcEmisor}&rr={$rfcReceptor}&tt={$totalFmt}&id={$uuid}";

        $context = stream_context_create([
            'http' => ['timeout' => 8],
            'ssl'  => [
                'verify_peer'       => true,
                'verify_peer_name'  => true,
                'allow_self_signed' => false,
            ],
        ]);

        try {
            $client = new SoapClient(
                'https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc?wsdl',
                [
                    'stream_context'      => $context,
                    'cache_wsdl'          => WSDL_CACHE_MEMORY,
                    'exceptions'          => true,
                    'connection_timeout'  => 8,
                ]
            );

            $resp = $client->Consulta(['expresionImpresa' => $expresion]);
            $result = $resp->ConsultaResult ?? null;

            $estado             = (string)($result->Estado ?? '');
            $codigoEstatus      = (string)($result->CodigoEstatus ?? '');
            $esCancelable       = isset($result->EsCancelable)        ? (string)$result->EsCancelable        : null;
            $estatusCancelacion = isset($result->EstatusCancelacion)  ? (string)$result->EstatusCancelacion  : null;

            $ok = ($estado === 'Vigente');

            return [
                'ok'                 => $ok,
                'estado'             => $estado,
                'codigoEstatus'      => $codigoEstatus,
                'esCancelable'       => $esCancelable,
                'estatusCancelacion' => $estatusCancelacion,
                'expresion'          => $expresion,
                'mensaje'            => $ok ? 'CFDI vigente en SAT.' : "CFDI no vigente: {$estado}",
                'raw'                => $resp,
            ];
        } catch (SoapFault $sf) {
            return [
                'ok'                 => false,
                'estado'             => 'ERROR',
                'codigoEstatus'      => 'SOAPFAULT',
                'esCancelable'       => null,
                'estatusCancelacion' => null,
                'expresion'          => $expresion,
                'mensaje'            => 'Error al consultar SAT: ' . $sf->getMessage(),
                'raw'                => null,
            ];
        } catch (\Throwable $e) {
            return [
                'ok'                 => false,
                'estado'             => 'ERROR',
                'codigoEstatus'      => 'EXCEPTION',
                'esCancelable'       => null,
                'estatusCancelacion' => null,
                'expresion'          => $expresion,
                'mensaje'            => 'Error al consultar SAT: ' . $e->getMessage(),
                'raw'                => null,
            ];
        }
    }
}
