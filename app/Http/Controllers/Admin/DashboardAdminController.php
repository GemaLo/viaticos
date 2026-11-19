<?php

namespace App\Http\Controllers\Admin;

use ZipArchive;
use App\Models\User;
use App\Models\Factura;
use App\Models\Comision;
use App\Models\PersonComision;
use App\Models\GastosSinComprobante;
use App\Jobs\ImportBeneficiarios;
use App\Mail\NewUserCredentials;
use App\Mail\NotificationFacturas;
use App\Mail\NotificacionActualizacion;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Exports\CommissionReportExport;
use App\Models\Objetive;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Jobs\GenerateCommissionReportJob;
use Illuminate\Support\Facades\Cache;

class DashboardAdminController extends Controller
{
    public function chunkSize(): int
    {
        return 1000;
    }
    protected $status_en_solicitud       = [1, 16];
    protected $status_en_autorizacionUr   = [9, 19];
    protected $status_en_revision         = [4, 21];
    protected $status_en_validacion       = [13, 22];
    protected $status_en_ampliacion       = [11];
    protected $status_en_autorizacionComp = [25, 26];
    protected $status_en_pagada           = [8, 24];
    protected $status_en_finalizada       = [20];

    public function index()
    {
        $user = Auth::user();
        $currentYear = Carbon::now()->year;

        $baseQuery = Comision::where('yearcomision', $currentYear);

        $data = [
            'nomusuario'                => $user->nomusuario,
            'idType'                    => $user->idtype,
            'unidadesDB'                => DB::table('direcciones')->get(),
            'count_en_solicitud'        => (clone $baseQuery)->whereIn('idstatuscomision', $this->status_en_solicitud)->count(),
            'count_en_autorizacionUr'   => (clone $baseQuery)->whereIn('idstatuscomision', $this->status_en_autorizacionUr)->count(),
            'count_en_revision'         => (clone $baseQuery)->whereIn('idstatuscomision', $this->status_en_revision)->count(),
            'count_en_validacion'       => (clone $baseQuery)->whereIn('idstatuscomision', $this->status_en_validacion)->count(),
            'count_en_ampliacion'       => (clone $baseQuery)->whereIn('idstatuscomision', $this->status_en_ampliacion)->count(),
            'count_en_autorizacionComp' => (clone $baseQuery)->whereIn('idstatuscomision', $this->status_en_autorizacionComp)->count(),
            'count_en_pagada'           => (clone $baseQuery)->whereIn('idstatuscomision', $this->status_en_pagada)->count(),
            'count_en_finalizada'       => (clone $baseQuery)->whereIn('idstatuscomision', $this->status_en_finalizada)->count(),
        ];

        return view('admin.Dashboard.index', $data);
    }

    public function getStatusPorUnidad(Request $request)
    {
        $unidad = $request->input('unidad_id');
        $modalidad = $request->input('modalidad');
        $currentYear = Carbon::now()->year;

        $queryBase = Comision::where('yearcomision', $currentYear);

        if ($unidad && $unidad !== 'todas') {
            $queryBase->where('oficio', $unidad);
        }

        if ($modalidad && $modalidad !== 'todas') {
            $queryBase->where('idmodalidad', $modalidad);
        }

        return response()->json([
            'solicitud'        => (clone $queryBase)->whereIn('idstatuscomision', $this->status_en_solicitud)->count(),
            'autorizacionUr'   => (clone $queryBase)->whereIn('idstatuscomision', $this->status_en_autorizacionUr)->count(),
            'revision'         => (clone $queryBase)->whereIn('idstatuscomision', $this->status_en_revision)->count(),
            'validacion'       => (clone $queryBase)->whereIn('idstatuscomision', $this->status_en_validacion)->count(),
            'ampliacion'       => (clone $queryBase)->whereIn('idstatuscomision', $this->status_en_ampliacion)->count(),
            'autorizacionComp' => (clone $queryBase)->whereIn('idstatuscomision', $this->status_en_autorizacionComp)->count(),
            'pagada'           => (clone $queryBase)->whereIn('idstatuscomision', $this->status_en_pagada)->count(),
            'finalizada'       => (clone $queryBase)->whereIn('idstatuscomision', $this->status_en_finalizada)->count(),
        ]);
    }


    public function users(Request $request)
    {
        $typeUsers = DB::table('typeUser')->get();
        $subcargos = DB::table('subcargos')->get();
        $query = User::query();
        $direcciones = DB::table('direcciones')->get();
        return view('admin.users', compact('typeUsers', 'subcargos', 'direcciones'));
    }

    public function datosEmpleado(Request $request)
    {
        if (!$request->has('empleado')) {
            return response()->json(['error' => 'El parámetro "empleado" es requerido.'], 400);
        }

        $numeroEmpleado = explode("-", $request->empleado)[0] ?? $request->empleado;
        $origen = $request->input('origen');
        $user = Auth::user();

        if ($origen === 'guardia') {
            $conexion = 'gnnomina';
            $tabla = 'EMPLEADOS_VIATICOS_GN';
            $fuente = 'Guardia Nacional';
        } else {
            $conexion = 'oracle_secondary';
            $tabla = 'EMPLEADOS_VIATICOS';
            $fuente = 'la Secretaría';
        }

        try {
            $emp = $this->buscarEnConexion($conexion, $tabla, $numeroEmpleado, $user);

            if (!$emp) {
                return response()->json([
                    'error' => "Empleado no encontrado en $fuente o fuera de su jurisdicción."
                ], 404);
            }

            return response()->json([
                'num_empleado'     => $emp->num_empleado,
                'nombre'           => $emp->nombre,
                'apellido_paterno' => $emp->apellido_paterno,
                'apellido_materno' => $emp->apellido_materno,
                'nivel'            => $emp->nivel,
                'unidad'           => $emp->unidad
            ]);
        } catch (\Exception $e) {
            \Log::error("Fallo de conexión en $conexion: " . $e->getMessage());

            return response()->json([
                'error' => "Temporalmente no se puede consultar la base de datos de $fuente. Intente más tarde."
            ], 503);
        }
    }
    private function buscarEnConexion($conexion, $tabla, $numeroEmpleado, $user)
    {
        $query = DB::connection($conexion)
            ->table($tabla)
            ->where('NUM_EMPLEADO', $numeroEmpleado);

        if ($user->idtype == 2 || $user->idtype == 3) {
            if (!empty($user->direcciones) && is_array($user->direcciones)) {
                $query->whereIn('UNIDAD', $user->direcciones);
            } else {
                $query->where('UNIDAD', '=', $user->unidad);
            }
        }
        return $query->first();
    }

    public function saveUser(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:255|unique:users',
            'origen' => 'required|string',
            'numEmpleado' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'numempleado')->where(function ($query) use ($request) {
                    return $query->where('procedencia', $request->origen);
                }),
            ],
        ];

        $messages = [
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.unique' => 'El correo electrónico ya ha sido registrado.',
            'numEmpleado.required' => 'El campo de número de empleado es obligatorio.',
            'numEmpleado.unique' => 'El número de empleado ya existe en esta dependencia.',
        ];

        $request->validate($rules, $messages);
        $randomPassword = Str::random(10);

        $idtype = (Auth::user()->idtype == 1) ? $request->get('idtype') : 3;

        $data = $request->all();
        $data['password'] = Hash::make($randomPassword);
        $data['active'] = 0;
        $data['idtype'] = $idtype;
        $data['procedencia'] = $request->get('origen');

        $user = User::create($data);

        $user = $user->fresh();
        $mail = $user->email;

        $tempFilePath = null;

        try {
            $templatePath = public_path('assets/file/carta_compromiso.docx');

            if (file_exists($templatePath)) {
                $tempFileName = 'carta_compromiso_' . time() . '_' . $user->numempleado . '.docx';
                $tempFilePath = storage_path('app/temp/' . $tempFileName);

                if (!is_dir(storage_path('app/temp'))) {
                    mkdir(storage_path('app/temp'), 0777, true);
                }

                $nombreCompleto = $user->nomusuario . ' ' . $user->appusuario . ' ' . $user->apmusuario;

                $templateProcessor = new TemplateProcessor($templatePath);
                $templateProcessor->setValue('nombre_completo', $nombreCompleto);
                $templateProcessor->setValue('num_empleado', $user->numempleado);
                $templateProcessor->setValue('correo_inst', $user->email);

                $templateProcessor->saveAs($tempFilePath);
            } else {
                Log::warning('La plantilla carta_compromiso.docx no existe, el correo se enviará sin adjunto.');
            }
        } catch (\Exception $e) {
            Log::error("Error al generar carta de compromiso: " . $e->getMessage());
            $tempFilePath = null;
        }

        try {
            Mail::to($mail)->send(new NewUserCredentials($mail, $randomPassword, $tempFilePath));

            if ($tempFilePath && file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }

            Session::flash('success', 'El usuario se ha creado correctamente, las credenciales han sido enviadas por correo.');
        } catch (\Exception $e) {
            Log::error("Fallo envío correo de credenciales: " . $e->getMessage());
            Session::flash('warning', 'El usuario se creó correctamente, pero falló el envío del correo de credenciales. Contraseña generada: ' . $randomPassword);
        }

        return back();
    }
    public function users_list_json()
    {
        $query = DB::connection('oracle_primary')->table('users')
            ->select(
                DB::raw("users.nomusuario || ' ' || users.appusuario || ' ' || users.apmusuario as name_complete"),
                'users.cargo as cargo',
                'users.unidad as unidad',
                'users.id_user as id_user',
                'users.numempleado as numempleado',
                'typeuser.typeuser as typeuser',
                'users.email as email',
                'users.active as active'
            )
            ->join('typeuser', 'typeuser.idtype', '=', 'users.idtype')
            ->orderBy('users.id_user', 'asc');

        if (Auth::check()) {
            $user = Auth::user();
            $direcciones = $user->direcciones;

            if ($user->idtype == 3) {
                $query->where('users.id_user', '=', $user->id_user);
            }

            if ($user->idtype == 2) {
                if (!empty($direcciones) && is_array($direcciones)) {
                    $query->where(function ($q) use ($direcciones) {
                        foreach ($direcciones as $dir) {
                            $q->orWhere('users.unidad', 'LIKE', '%' . $dir . '%');
                        }
                    });
                } else {
                    $query->where('users.unidad', '=', $user->unidad);
                }
            }
            $users = [1, 29, 4389];

            if (Auth::user()->idtype == 1 && !in_array(Auth::user()->id_user, $users)) {

                $query->where(function ($q) {
                    $q->where('users.restringido', '=', 0)
                        ->orWhereNull('users.restringido');
                });
            }
        }
        $jsonData = DataTables::of($query)
            ->filterColumn('name_complete', function ($query, $keyword) {
                $query->whereRaw("upper(users.nomusuario || ' ' || users.appusuario || ' ' || users.apmusuario) LIKE ?", ["%" . strtoupper($keyword) . "%"]);
            })
            ->editColumn('active', function ($user) {
                return $user->active == 1
                    ? '<span class="badge bg-success">Activo</span>'
                    : '<span class="badge bg-danger">Inactivo</span>';
            })
            ->addColumn('btn_edit', 'buttons.button_user')
            ->rawColumns(['btn_edit', 'active'])
            ->toJson();

        return $jsonData;
    }
    public function editUser($idUserHash)
    {
        try {
            try {
                $idUser = Crypt::decryptString($idUserHash);
            } catch (DecryptException $e) {
                return redirect()->back()->with('error', 'ID de usuario no válido o alterado.');
            }

            $typeUsers = DB::table('typeUser')->get();
            $subcargos = DB::table('subcargos')->get();
            $direccionesDB = DB::table('direcciones')->get();
            $historialSedes = DB::table('historialEmployee')
                ->where('id_user', $idUser)
                ->orderBy('created_at', 'desc')
                ->get();
            
            $query = DB::connection('oracle_primary')->table('users')
                ->select(
                    DB::raw("users.nomusuario || ' ' || users.appusuario || ' ' || users.apmusuario as name_complete"),
                    'users.cargo',
                    'users.nomusuario',
                    'users.appusuario',
                    'users.apmusuario',
                    'users.numempleado',
                    'users.password',
                    'users.unidad',
                    'users.procedencia',
                    'users.restringido',
                    'users.id_user',
                    'users.idtype',
                    'users.direcciones',
                    'users.idsubcargo',
                    'users.importcomplete',
                    'users.email',
                    'users.active',
                    'users.contacto',
                    'typeuser.typeuser as nombre_del_perfil',
                    'personcomision.banco',
                    'personcomision.clabe',
                    'personcomision.cuenta'
                )
                ->join('typeuser', 'typeuser.idtype', '=', 'users.idtype')
                ->leftjoin('personcomision', 'users.id_user', '=', 'personcomision.id_user')
                ->where('users.id_user', '=', $idUser)
                ->first();

            if (!$query) {
                return redirect()->back()->with('error', 'Usuario no encontrado en el sistema.');
            }

            if (is_string($query->direcciones)) {
                $query->direcciones = json_decode($query->direcciones, true) ?: [];
            }

            $datosReporte = null;

            try {
                if ($query->procedencia === 'SECRETARÍA') {
                    $datosReporte = DB::connection('oracle_secondary')
                        ->table('EMPLEADOS_VIATICOS')
                        ->where('NUM_EMPLEADO', '=', $query->numempleado)
                        ->first();
                } elseif ($query->procedencia === 'GUARDIA') {
                    $datosReporte = DB::connection('gnnomina')
                        ->table('EMPLEADOS_VIATICOS_GN')
                        ->where('NUM_EMPLEADO', '=', $query->numempleado)
                        ->first();
                }
            } catch (\Exception $e_nomina) {
                \Log::warning("No se pudo obtener información de nómina: " . $e_nomina->getMessage());
            }

            return view('admin.editUsuario', compact('query', 'datosReporte', 'typeUsers', 'subcargos', 'direccionesDB', 'historialSedes'));
        } catch (\Exception $e) {
            \Log::critical("Error crítico en editUser: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al intentar cargar el usuario. Por favor, intente más tarde.');
        }
    }

    public function updateUser(Request $request)
    {
        // return $request;

        $validatedData = $request->validate([
            'id_user'          => 'required|numeric|exists:users,id_user',
            'email'            => 'required|email',
            'active'           => 'nullable|boolean',
            'idtype'           => 'required',
            'idsubcargo'       => 'nullable',
            'reset_password'   => 'nullable|boolean',
            'send_commitment'  => 'nullable|boolean',
            'direcciones'      => 'nullable|array',
            'importcomplete'   => 'nullable',
            'procedencia'      => 'nullable',
            'restringido'      => 'nullable',
            'cargo'            => 'required',
            'unidad'           => 'required',
            'nomUsuario'       => 'required',
            'apmUsuario'       => 'required',
            'appUsuario'       => 'required',
            'numEmpleado'      => 'required|string',
        ]);

        $user = User::findOrFail($validatedData['id_user']);

        $nuevoOrigen = $validatedData['procedencia'] ?? null;
        $nuevoNumero = $validatedData['numEmpleado'];

        $registroActivo = \App\Models\HistorialEmployee::where('id_user', $user->id_user)
            ->where('active', 1)
            ->first();

        $huboCambio = false;
        $numeroAnteriorRegistrado = null;

        if (!$registroActivo) {
            $huboCambio = true;
            $numeroAnteriorRegistrado = $user->numempleado;
        } else {
            if ($registroActivo->numero_nuevo !== $nuevoNumero || $registroActivo->origin !== $nuevoOrigen) {
                $huboCambio = true;
                $numeroAnteriorRegistrado = $registroActivo->numero_nuevo;
            }
        }

        if ($huboCambio) {
            if ($registroActivo) {
                $registroActivo->update(['active' => 0]);
            }

            \App\Models\HistorialEmployee::create([
                'id_user'         => $user->id_user,
                'origin'          => 'GUARDIA',
                'numero_anterior' => $numeroAnteriorRegistrado,
                'numero_nuevo'    => $nuevoNumero,
                'active'          => 1,
            ]);
        }

        $dataToUpdate = [
            'email'          => $validatedData['email'],
            'idtype'         => $validatedData['idtype'],
            'idsubcargo'     => $validatedData['idsubcargo'],
            'cargo'          => $validatedData['cargo'],
            'unidad'         => $validatedData['unidad'],
            'nomUsuario'     => $validatedData['nomUsuario'],
            'apmUsuario'     => $validatedData['apmUsuario'],
            'appUsuario'     => $validatedData['appUsuario'],
            'importcomplete' => $validatedData['importcomplete'],
            'active'         => $request->has('active') ? $validatedData['active'] : $user->active,
            'direcciones'    => $validatedData['direcciones'] ?? [],
            'procedencia'    => $nuevoOrigen,
            'numEmpleado'    => $nuevoNumero,
            'restringido'    => $validatedData['restringido'] ?? null,
        ];

        $randomPassword = null;
        if ($request->boolean('reset_password')) {
            $randomPassword = Str::random(10);
            $dataToUpdate['password']              = Hash::make($randomPassword);
            $dataToUpdate['must_change_password'] = 1;
        }

        $user->update($dataToUpdate);

        DB::table('registros')->insert([
            'movimiento' => "HA ACTUALIZADO EL USUARIO (Cambio de número/sede): $user->email",
            'id_user' => Auth::user()->id_user,
            'fecha_movimiento' => now(),
            'created_at' => date('Y-m-d-H:i:s'),
        ]);

        $sendCommitment = $request->boolean('send_commitment');

        if ($randomPassword || $sendCommitment) {
            $tempFilePath = null;

            try {
                if ($sendCommitment) {
                    $templatePath = public_path('assets/file/carta_compromiso.docx');

                    if (file_exists($templatePath)) {
                        $tempDir = storage_path('app/temp');
                        if (!is_dir($tempDir)) mkdir($tempDir, 0777, true);

                        $tempFilePath = $tempDir . '/carta_compromiso_' . time() . '_' . $user->id_user . '.docx';

                        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
                        $templateProcessor->setValue('nombre_completo', trim($user->nomusuario . ' ' . $user->appusuario . ' ' . $user->apmusuario));
                        $templateProcessor->setValue('num_empleado', $user->numempleado);
                        $templateProcessor->setValue('correo_inst', $user->email);
                        $templateProcessor->saveAs($tempFilePath);
                    } else {
                        Log::warning('La plantilla carta_compromiso.docx no existe, el correo no contendrá el adjunto.');
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error al generar la carta compromiso: ' . $e->getMessage());
                $tempFilePath = null;
            }

            try {
                $passwordForEmail = $randomPassword ?: 'Contraseña Actual (No Modificada)';
                Mail::to($user->email)->send(new \App\Mail\NewUserCredentials($user->email, $passwordForEmail, $tempFilePath));

                if ($tempFilePath && file_exists($tempFilePath)) {
                    unlink($tempFilePath);
                }

                return redirect()->back()->with('success', 'Usuario actualizado y notificación enviada.');
            } catch (\Exception $e) {
                Log::error('Fallo en envío de correo actualizar usuario: ' . $e->getMessage());
                if ($tempFilePath && file_exists($tempFilePath)) {
                    unlink($tempFilePath);
                }
                return redirect()->back()->with('warning', 'Datos actualizados, pero el correo falló: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:51200',
        ]);

        $file = $request->file('csv_file');

        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('temp', $filename, 'local');

        $absolutePath = Storage::disk('local')->path($path);

        $importKey = 'import_beneficiarios_' . Str::uuid();
        Cache::put($importKey, ['status' => 'pending', 'processed' => 0], 1800);

        ImportBeneficiarios::dispatch($absolutePath, $importKey);

        return redirect()->back()->with('import_key', $importKey);
    }

    public function checkImportStatus($key)
    {
        $status = Cache::get($key, ['status' => 'not_found', 'processed' => 0]);
        return response()->json($status);
    }

    public function downloadComisionFilesZip(Request $request)
    {
        $comisionIds = $request->input('ids');

        if (empty($comisionIds)) {
            Log::warning("Descarga ZIP: No se proporcionó ningún ID de comisión.");
            return back()->withErrors('Error: Debe seleccionar al menos una comisión para descargar archivos.');
        }

        $facturas = Factura::with('comision')->whereIn('idcomision', $comisionIds)->where('cancelacion', '=', '2')->get();
        $gastosSinComprobante = GastosSinComprobante::with('comision')->whereIn('idcomision', $comisionIds)->get();

        $zipFilePath = tempnam(sys_get_temp_dir(), 'comision_zip_');
        $zip = new ZipArchive;

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            Log::error("Error al crear el archivo ZIP temporal: {$zipFilePath}");
            return back()->withErrors('Error: No se pudo crear el archivo ZIP temporal.');
        }

        $diskName = 'facturas';
        $fileCounter = 1;

        foreach ($facturas as $factura) {
            $pdfFileName = $factura->pdf_path;
            $folio = $factura->comision->comision ?? 'S-N';
            $year = $factura->comision->yearcomision ?? date('Y');
            $zipFolder = "COMISION_{$folio}_{$year}/";

            if (!empty($pdfFileName)) {
                if (Storage::disk($diskName)->exists($pdfFileName)) {
                    $sourcePath = Storage::disk($diskName)->path($pdfFileName);
                    $zip->addFile($sourcePath, $zipFolder . "FACTURA_{$fileCounter}_" . basename($pdfFileName));
                }

                $xmlFileName = preg_replace('/\.(pdf|PDF)$/i', '.xml', $pdfFileName);
                if (Storage::disk($diskName)->exists($xmlFileName)) {
                    $sourcePath = Storage::disk($diskName)->path($xmlFileName);
                    $zip->addFile($sourcePath, $zipFolder . "XML_{$fileCounter}_" . basename($xmlFileName));
                }
                $fileCounter++;
            }
        }

        foreach ($gastosSinComprobante as $gasto) {
            $proofFileName = $gasto->proof_file;
            $comisionId = $gasto->idcomision;

            $folio = $gasto->comision->comision ?? 'S-N';
            $year = $gasto->comision->yearcomision ?? date('Y');
            $zipFolder = "COMISION_{$folio}_{$year}/";

            if (!empty($proofFileName)) {
                $relativeStoragePath = "public/tickets/{$comisionId}/{$proofFileName}";

                if (Storage::disk($diskName)->exists($relativeStoragePath)) {
                    $sourcePath = Storage::disk($diskName)->path($relativeStoragePath);
                    $zip->addFile($sourcePath, $zipFolder . "SINCOMPROBANTE_{$fileCounter}_" . basename($proofFileName));
                }
                $fileCounter++;
            }
        }

        if ($zip->numFiles == 0) {
            $zip->addFromString("nota.txt", "No se encontraron archivos físicos.");
        }

        $zip->close();

        $downloadFileName = 'comprobantes_' . date('Ymd_His') . '.zip';
        return response()->download($zipFilePath, $downloadFileName)->deleteFileAfterSend(true);
    }
    public function comprobacionEnd($idComision)
    {
        $comision = Comision::with(['personComision.user', 'facturas'])
            ->findOrFail($idComision);

        $comisionado = $comision->personComision->user;
        $recipientEmail = $comision->personComision->user->email ?? null;
        $unidadComisionado = $comisionado->unidad ?? null;

        $ccEmail = null;
        if ($unidadComisionado) {
            $userCc = User::where('unidad', $unidadComisionado)
                ->where('idtype', 2)
                ->first();

            $ccEmail = $userCc->email ?? null;
        }


        if ($recipientEmail) {
            try {
                $mailable = new NotificationFacturas($comision);

                $mailer = Mail::to($recipientEmail);

                if ($ccEmail) {
                    $mailer->cc($ccEmail);
                }

                $mailer->send($mailable);

                $message = 'Comprobación finalizada y correo de notificación enviado a ' . $recipientEmail;
                if ($ccEmail) {
                    $message .= ' (con copia a ' . $ccEmail . ')';
                }
                return redirect()->back()->with('success', $message);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Comprobación finalizada, pero hubo un error al enviar el correo: ' . $e->getMessage());
            }
        }
        return redirect()->back()->with('warning', 'Comprobación finalizada, pero no se encontró un correo para notificar.');
    }


    public function downloadReport(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return back()->with('error', 'Sesión expirada. Por favor, vuelve a iniciar sesión.');
            }

            // Validar coherencia del rango de folios
            if ($request->filled('folio_inicio') && $request->filled('folio_fin')) {
                if ((int)$request->folio_inicio > (int)$request->folio_fin) {
                    return back()->with('error', 'El Folio Inicial no puede ser mayor que el Folio Final.');
                }
            }

            $filters = array_filter($request->only([
                'idtypecomision',
                'initialdate',
                'finaldate',
                'idstatuscomision',
                'iddestino',
                'idmodalidad',
                'direcciones',
                'folio_inicio', // Captura del nuevo campo
                'folio_fin'     // Captura del nuevo campo
            ]), fn($value) => !is_null($value) && $value !== '');

            if ($user->idtype == 2) {
                $filters['validador_numempleado'] = $user->numempleado;
            }

            $folderPath = storage_path('app/public/reportes');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $reportId = (string) \Illuminate\Support\Str::uuid();
            $fileName = "reportes/reporte_comisiones_{$reportId}.xlsx";

            \Illuminate\Support\Facades\Cache::put("report_{$reportId}", [
                'status' => 'pending',
                'file_name' => $fileName,
                'file_path' => null,
                'error_message' => null
            ], 3600);

            \App\Jobs\GenerateCommissionReportJob::dispatch($reportId, $fileName, $filters, $user->idtype);

            return back()->with([
                'info' => 'El reporte se está procesando en segundo plano, en breve estará disponible.',
                'download_id' => $reportId
            ]);
        } catch (\Exception $e) {
            \Log::error("Error al iniciar reporte asíncrono: " . $e->getMessage());
            return back()->with('error', 'Error al procesar el reporte. ' . $e->getMessage());
        }
    }

    public function checkReportStatus($id)
    {
        $reportData = \Illuminate\Support\Facades\Cache::get("report_{$id}");

        if (!$reportData) {
            return response()->json([
                'status' => 'failed',
                'error' => 'El reporte no se encuentra en el servidor.'
            ], 404);
        }

        $fileUrl = $reportData['file_name']
            ? asset(\Illuminate\Support\Facades\Storage::url($reportData['file_name']))
            : null;

        return response()->json([
            'status' => $reportData['status'],
            'file_url' => $fileUrl,
            'error' => $reportData['error_message']
        ]);
    }
    public function update(Request $request, $id)
    {
        $urJson = json_encode($request->input('ur'));

        DB::table('objetives')
            ->where('idobjetive', $id)
            ->update([
                'objetive' => $request->input('objetive'),
                'ur'        => $urJson
            ]);

        return redirect()->back()->with('success', 'Objetivo actualizado correctamente');
    }
    public function objetives_list_json()
    {
        $query = DB::connection('oracle_primary')->table('objetives')
            ->select(
                DB::raw('dbms_lob.substr(objetives.objetive, 4000, 1) as objetive'),
                'objetives.ur as ur',
                'objetives.idobjetive as idobjetive'
            )
            ->orderBy('objetive', 'asc');

        $jsonData = DataTables::of($query)
            ->toJson();

        return $jsonData;
    }
}
