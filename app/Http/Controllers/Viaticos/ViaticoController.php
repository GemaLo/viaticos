<?php

namespace App\Http\Controllers\Viaticos;

use App\Http\Controllers\Controller;
use PDF;
use ZipArchive;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Import;
use App\Models\Factura;
use App\Models\Destino;
use App\Models\Comision;
use App\Models\Objetive;
use App\Models\Modalidad;
use App\Models\Transport;
use App\Models\Reintegro;
use App\Models\Reasignacion;
use App\Models\TypeComision;
use App\Models\StatusComision;
use App\Models\PersonComision;
use App\Models\GastosSinComprobante;
use App\Mail\NotificationNewComission;
use App\Mail\NotificacionDevengada;
use App\Mail\NotificacionAmpliacion;
use App\Mail\NotificationStatusMail;
use App\Mail\NotificacionValidacion;
use App\Mail\NotificationPayMail;
use App\Mail\NotificationRemanente;
use App\Mail\NotificacionSecretariado;
use App\Models\Registros;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class ViaticoController extends Controller
{
    public function index()
    {
        return view('pages.FormViaticos');
    }

    public function comisionView()
    {
        $sistemaCerrado = true;

        if ($sistemaCerrado) {
            Session::flash('warning', 'Sistema temporalmente cerrado.');
            return redirect()->back();
        }

        $user = Auth::user();
        $numEmpleado = $user->numempleado;
        $idUser = $user->id_user;
        $origen = $user->procedencia;
        $destinos = DB::table('destinos')->get();

        $unidad = $user->unidad;

        $filteredObjetives = Objetive::get();
        // return $origen;
        if ($origen === 'GUARDIA') {
            $conexion = 'gnnomina';
            $tabla = 'EMPLEADOS_VIATICOS_GN';
            $fuente = 'Guardia Nacional';
        } else {
            $conexion = 'oracle_secondary';
            $tabla = 'EMPLEADOS_VIATICOS';
            $fuente = 'la Secretaría';
        }

        try {
            $datosReporte = $this->buscarEnConexion($conexion, $tabla, $numEmpleado, $user);
            if (empty($datosReporte)) {
                $datosReporte = DB::connection('oracle_primary')
                    ->table('comisiones')
                    ->select(
                        'direcciones.nombre_ur as nombre_unidad',
                        'direcciones.no_direccion as unidad',
                        'personcomision.direccion as domicilio_unidad',
                        'users.nivel as nivel',
                        'users.numempleado as num_empleado',
                        DB::raw("users.nomusuario || ' ' || users.appusuario || ' ' || users.apmusuario as nombre_completo"),
                        'personcomision.cargo as cargo',
                        'personcomision.rfc',
                        'personcomision.curp'
                    )
                    ->leftJoin('personcomision', 'personcomision.idpersoncomision', '=', 'comisiones.idpersoncomision')
                    ->leftJoin('users', 'personcomision.id_user', '=', 'users.id_user')
                    ->leftJoin('direcciones', 'direcciones.no_direccion', '=', 'users.unidad')
                    ->where('users.id_user', '=', $idUser)
                    ->first();
            }

            if (empty($datosReporte)) {
                Session::flash('warning', 'No se pudo obtener información de viáticos para su número de empleado en ningún sistema.');
                return redirect()->back();
            }

            if ($user->importcomplete != NULL) {
                $import = (object) [
                    'completo' => $user->importcomplete,
                    'medio'    => $user->importcomplete / 2
                ];
            } else {
                if (!isset($datosReporte->nivel)) {
                    Session::flash('warning', 'El empleado no tiene un nivel asignado para calcular los importes.');
                    return redirect()->back();
                }

                $import = $this->calcularImportes($datosReporte->nivel);

                if ($import->completo == 0) {
                    Session::flash('warning', 'No se pudo determinar el importe para el nivel: ' . $datosReporte->nivel);
                    return redirect()->back();
                }
            }

            $destinos = Destino::all();
            $typeComision = TypeComision::all();
            $typeTransporte = Transport::all();
            $modalidades = Modalidad::all();
            $personComision = PersonComision::where('id_user', '=', $idUser)->first();
            $subcargosPermitidos = [2, 3];

            $query = DB::table('users')
                ->select('nomusuario', 'appusuario', 'apmusuario', 'numempleado', 'email', 'unidad', 'idsubcargo')
                ->where('idtype', '=', 2)
                ->where('active', '=', 1)
                ->when(!in_array(Auth::user()->idsubcargo, $subcargosPermitidos), function ($q) {
                    return $q->where('idsubcargo', '!=', 4)
                        ->orWhere('id_user', '=', '4281');
                })
                ->orderBy('nomusuario', 'ASC')
                ->get();


            return view('comision.comision', compact(
                'datosReporte',
                'typeComision',
                'typeTransporte',
                'destinos',
                'import',
                'personComision',
                'query',
                $modalidades,
                'destinos',
                'filteredObjetives'
            ));
        } catch (\Exception $e) {
            \Log::error("Error en comisionView: " . $e->getMessage());

            Session::flash('error', 'El servicio de consulta de viáticos no está disponible en este momento. Intente más tarde.');
            return redirect()->back();
        }
    }

    private function buscarEnConexion($conexion, $tabla, $numEmpleado, $user)
    {
        $query = DB::connection($conexion)
            ->table($tabla)
            ->where('NUM_EMPLEADO', $numEmpleado);

        return $query->first();
    }

    private function calcularImportes($nivel)
    {
        $nivel = trim($nivel);

        if (strpos($nivel, 'GN') === 0) {
            $mapaGN = [
                'GNC002' => ['completo' => 2850, 'medio' => 1425],
                'GNC003' => ['completo' => 1700, 'medio' => 850],
                'GNI001' => ['completo' => 1700, 'medio' => 850],
                'GNI002' => ['completo' => 1700, 'medio' => 850],
                'GNI003' => ['completo' => 1700, 'medio' => 850],
                'GNO001' => ['completo' => 1700, 'medio' => 850],
                'GNO002' => ['completo' => 1700, 'medio' => 850],
                'GNO003' => ['completo' => 1700, 'medio' => 850],
                'GNO004' => ['completo' => 1700, 'medio' => 850],
                'GNB001' => ['completo' => 1700, 'medio' => 850],
                'GNB002' => ['completo' => 1700, 'medio' => 850],
                'GNB003' => ['completo' => 1700, 'medio' => 850],
                'GNB004' => ['completo' => 1700, 'medio' => 850],
            ];

            if (isset($mapaGN[$nivel])) {
                return (object) $mapaGN[$nivel];
            }
        }

        $letra = substr($nivel, 0, 1);

        if (in_array($letra, ['J', 'I', 'H', 'G'])) {
            return (object) ['completo' => 2850, 'medio' => 1425];
        }

        if (in_array($letra, ['P', 'O', 'M', 'N', 'L', 'K'])) {
            return (object) ['completo' => 1700, 'medio' => 850];
        }

        return (object) ['completo' => 980, 'medio' => 490];
    }

    // public function generarFolio(): int
    // {
    //     return Cache::lock('lock:folio_generation', 10)->block(5, function () {
    //         $currentYear = date('Y');
    //         $ultimoFolio = Comision::where('yearcomision', $currentYear)->max('folio');
    //         $nextFolio = ($ultimoFolio) ? $ultimoFolio + 1 : 1;
    //         return $nextFolio;
    //     });
    // }

    private function asignarNumeroComision($idcomision)
    {
        return Cache::lock('lock:comision_assignment', 10)->block(5, function () use ($idcomision) {
            return DB::connection('oracle_primary')->transaction(function () use ($idcomision) {

                $year = date('Y');

                $ultimoFolio = Comision::on('oracle_primary')
                    ->whereNotNull('comision')
                    ->whereYear('created_at', $year)
                    ->orderByRaw('TO_NUMBER(comision) DESC')
                    ->lockForUpdate()
                    ->first();

                $nuevoNumero = $ultimoFolio ? ((int)$ultimoFolio->comision + 1) : 1;

                $comision = Comision::on('oracle_primary')
                    ->where('idcomision', $idcomision)
                    ->lockForUpdate()
                    ->first();

                if (!$comision) {
                    throw new \Exception("La comisión {$idcomision} no existe.");
                }

                $comision->comision = $nuevoNumero;
                $comision->save();

                return $nuevoNumero;
            });
        });
    }

    public function saveComision(Request $request)
    {
        // return $request;
        $idUser = Auth::id();
        $modalidad = $request->get('idmodalidad');

        $requestInitialDate = Carbon::parse($request->get('initialdate'));
        $requestFinalDate = Carbon::parse($request->get('finaldate'));

        $personComision = PersonComision::firstOrNew(['id_user' => $idUser]);
        $personComision->fill($request->all());

        if (!$personComision->exists) {
            $personComision->id_user = $idUser;
        }

        // if ($modalidad == 1 || $modalidad == 2) {
        //     $excludedStatuses = [3, 6, 13, 14, 15, 18, 22, 24, 25, 26, 27, 28];

        //     $activeCommissionsCount = Comision::where('idpersoncomision', $personComision->idpersoncomision)
        //         ->whereNotIn('idstatuscomision', $excludedStatuses)
        //         ->where('idmodalidad', '=', $modalidad)
        //         ->count();

        //     if ($activeCommissionsCount >= 2) {
        //         Session::flash('warning', 'No se puede registrar la comisión. Solo se permiten dos comisiones pendientes.');
        //         return redirect('Dashboard');
        //     }
        // }

        $cancelStatuses = [3, 18];

        $comissionExist = Comision::where('idpersoncomision', $personComision->idpersoncomision)
            ->where(function ($query) use ($requestInitialDate, $requestFinalDate, $cancelStatuses) {
                $query->where('initialdate', '<=', $requestFinalDate->toDateString())
                    ->where('finaldate', '>=', $requestInitialDate->toDateString());
            })
            ->whereNotIn('idstatuscomision', $cancelStatuses)
            ->exists();

        if ($comissionExist) {
            Session::flash('warning', 'Error: Las fechas seleccionadas coinciden o se cruzan con una comisión ya activa.');
            return redirect('Dashboard');
        }

        if ($request->hasFile('estado_cuenta_file')) {
            $file = $request->file('estado_cuenta_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('estados', $fileName);
            $personComision->estado_cuenta_file = $fileName;
        }
        $personComision->save();
        $personComision->refresh();

        $user = Auth::user();
        if ($user) {
            $user->update([
                'cargo'  => $request->get('nivel'),
                'unidad' => $request->get('unidad'),
            ]);
        }

        $destino = $request->get('idtypecomision') == 1 ? $request->get('destino') : $request->get('destino_internacional');
        $infoUser = User::where('numempleado', $request->get('num_empleado'))->first();
        $validador = User::where('numempleado', $request->get('validador'))->first();

        $dataComision = Cache::lock('lock:folio_generation', 10)->block(5, function () use ($request, $personComision, $destino, $validador, $modalidad) {
            $currentYear = date('Y');

            $ultimoFolio = Comision::where('yearcomision', $currentYear)->max('folio');
            $nextFolio = $ultimoFolio ? $ultimoFolio + 1 : 1;

            $newStatus = 1;
            $numeroComision = null;
            $autorizacion = null;

            if ($validador && $validador->idsubcargo == 4) {
                $newStatus = ($modalidad == 1) ? 2 : 17;

                $maxComisionRef = Comision::whereYear('created_at', $currentYear)
                    ->whereNotNull('comision')
                    ->whereRaw("REGEXP_LIKE(comision, '^[0-9]+$')")
                    ->max(DB::raw('TO_NUMBER(comision)'));

                $numeroComision = $maxComisionRef ? (int)$maxComisionRef + 1 : 1;
                $autorizacion = 1;
            }
            if (Auth::user()->restringido == 1) {
                $confidential = 1;
            } else {
                $confidential = null;
            }
            return Comision::create(array_merge($request->all(), [
                'idstatuscomision' => $newStatus,
                'idpersoncomision' => $personComision->idpersoncomision,
                'yearcomision'     => $currentYear,
                'folio'            => $nextFolio,
                'nivelComision'    => $request->get('nivel'),
                'puestoComision'   => $request->get('cargo'),
                'origen'           => 'CDMX',
                'destino'          => $destino,
                'status'          => 1,
                'autorizacion'     => $autorizacion,
                'clasificado'      =>  $confidential,
            ]));
        });

        Session::flash('success', '✅ Registro exitoso. Folio: ' . $dataComision->folio . ($dataComision->comision ? " | Comisión: " . $dataComision->comision : ""));

        if (!empty($request->get('email')) && $validador->idsubcargo != 4) {
            try {
                Mail::to($request->get('email'))->queue(new NotificationNewComission($dataComision, $infoUser));
            } catch (\Exception $e) {
                \Log::error("Error Mail: " . $e->getMessage());
            }
        }

        if ($validador->idsubcargo == 4) {
            try {
                Mail::to($request->get('email'))->queue(new NotificacionSecretariado($dataComision, $infoUser));
            } catch (\Exception $e) {
                \Log::error("Error Mail: " . $e->getMessage());
            }
        }

        return (Auth::user()->idtype == 1) ? redirect('/Dashboard-Admin') : redirect('Dashboard');
    }
    // public function saveComision(Request $request)
    // {
    //     $idUser = Auth::id();
    //     $modalidad = $request->get('modalidad');

    //     $requestInitialDate = Carbon::parse($request->get('initialDate'));
    //     $requestFinalDate = Carbon::parse($request->get('finalDate'));

    //     $personComision = PersonComision::firstOrNew(['id_user' => $idUser]);
    //     $personComision->fill($request->all());

    //     if (!$personComision->exists) {
    //         $personComision->id_user = $idUser;
    //     }

    //     if ($request->hasFile('estado_cuenta_file')) {
    //         $file = $request->file('estado_cuenta_file');
    //         $fileName = time() . '_' . $file->getClientOriginalName();
    //         $file->storeAs('estados', $fileName);
    //         $personComision->estado_cuenta_file = $fileName;
    //     }

    //     $personComision->save();
    //     $personComision->refresh();

    //     if ($modalidad == 1) {
    //         $excludedStatuses = [3, 6, 14, 15, 18, 24];

    //         $activeCommissionsCount = Comision::where('idpersoncomision', $personComision->idpersoncomision)
    //             ->whereNotIn('idstatuscomision', $excludedStatuses)
    //             ->where('idmodalidad', 1)
    //             ->count();

    //         if ($activeCommissionsCount >= 2) {
    //             Session::flash('warning', 'No se puede registrar la comisión. En Modalidad 1 solo se permiten dos comisiones pendientes.');
    //             return redirect('Dashboard');
    //         }

    //         $comissionExist = Comision::where('idpersoncomision', $personComision->idpersoncomision)
    //             ->whereNotIn('idstatuscomision', $excludedStatuses)
    //             ->whereDate('initialdate', '<=', $requestFinalDate->toDateString())
    //             ->whereDate('finaldate', '>=', $requestInitialDate->toDateString())
    //             ->exists();

    //         if ($comissionExist) {
    //             Session::flash('warning', 'No se ha guardado la comisión. Ya existe una comisión activa que coincide con las fechas.');
    //             return redirect('Dashboard');
    //         }
    //     }
    //     if($request->get('idtypecomision') == 1){
    //         $destino = $request->get('destino');
    //     }
    //     else {
    //         $destino = $request->get('destino_internacional');
    //     }
    //     $dataToCreate = $request->all() + [
    //         'idstatuscomision' => 1,
    //         'idpersoncomision' => $personComision->idpersoncomision,
    //         'yearcomision' => date('Y'),
    //         'folio' => $this->generarFolio(),
    //         'comision' => null,
    //         'origen' => 'CDMX',
    //         'destino' => $destino
    //     ];

    //     $infoUser = User::where('numempleado', '=', $request->get('num_empleado'))->first();
    //     $dataComision = Comision::create($dataToCreate);

    //     Session::flash('success', '✅ Se ha registrado la comisión con folio: ' . $dataComision->folio);

    //     $recipientEmail = $request->get('email');
    //     if (!empty($recipientEmail)) {
    //         try {
    //             Mail::to($recipientEmail)->send(new NotificationNewComission($dataComision, $infoUser));
    //         } catch (\Exception $e) {
    //             \Log::error("Error al enviar correo: " . $e->getMessage());
    //         }
    //     }

    //     return (Auth::user()->idtype == 1) ? redirect('/Dashboard-Admin') : redirect('Dashboard');
    // }



    public function comision_list_json(Request $request)
    {
        ini_set('memory_limit', '512M');

        $user = Auth::user();

        $direccionesACargo = is_array($user->direcciones)
            ? $user->direcciones
            : json_decode($user->direcciones, true);
        $direccionesACargo = array_map('trim', (array)$direccionesACargo);

        $query = DB::connection('oracle_primary')->table('comisiones')
            ->select(
                'comisiones.idcomision',
                'comisiones.comision',
                'comisiones.importe',
                'comisiones.oficio',
                'comisiones.yearcomision',
                'comisiones.initialdate',
                'comisiones.autorizacion',
                'comisiones.finaldate',
                'comisiones.destino',
                'comisiones.clasificado',
                'comisiones.clc',
                'typecomision.typecomision',
                'typecomision.idtypecomision',
                'modalidades.idmodalidad',
                'modalidades.modalidad',
                'personcomision.cargo',
                'users.id_user',
                'users.unidad',
                'statuscomision.statuscomision',
                'statuscomision.idstatuscomision',
                DB::raw("users.nomusuario || ' ' || users.appusuario || ' ' || users.apmusuario as nombre_completo"),
                DB::raw("(SELECT count(*) FROM facturas f WHERE f.idcomision = comisiones.idcomision AND f.cancelacion = 1) as facturas_canceladas_count"),
                DB::raw("(SELECT count(*) FROM facturas f WHERE f.idcomision = comisiones.idcomision AND f.STATUS = 'Cancelado') as facturas_canceladas_sat")
            )
            ->leftJoin('typecomision', 'typecomision.idtypecomision', '=', 'comisiones.idtypecomision')
            ->leftJoin('personcomision', 'personcomision.idpersoncomision', '=', 'comisiones.idpersoncomision')
            ->leftJoin('users', 'personcomision.id_user', '=', 'users.id_user')
            ->leftJoin('modalidades', 'modalidades.idmodalidad', '=', 'comisiones.idmodalidad')
            ->leftJoin('statuscomision', 'statuscomision.idstatuscomision', '=', 'comisiones.idstatuscomision')
            ->orderBy('comisiones.comision', 'DESC');

        if ($user->idtype == 3) {
            $query->where('users.id_user', $user->id_user);
        } elseif ($user->idtype == 2) {
            if ($user->idsubcargo != 4 && $user->id_user != 4281) {
                if (!empty($direccionesACargo)) {
                    $query->where(function ($q) use ($direccionesACargo, $user) {
                        $q->whereIn('users.unidad', $direccionesACargo)
                            ->orWhere('comisiones.validador', $user->numempleado)
                            ->orWhere('users.numempleado', $user->numempleado);
                    });
                }
            } else {
                $query->where('comisiones.validador', $user->numempleado);
            }
        } elseif ($user->idtype == 1) {
            $query->where('comisiones.autorizacion', '1')
                ->whereNotNull('comisiones.oficio');

            $usuariosEspeciales = [1, 29, 4389];

            if (!in_array($user->id_user, $usuariosEspeciales)) {
                $query->where(function ($q) {
                    $q->where('comisiones.clasificado', '=', 0)
                        ->orWhereNull('comisiones.clasificado');
                });
            }
        }

        $tabType = $request->input('tabType');
        if (is_numeric($tabType)) {
            $query->where('comisiones.idmodalidad', $tabType);
        }

        return DataTables::of($query)
            ->filterColumn('nombre_completo', function ($q, $keyword) {
                $q->whereRaw("upper(users.nomusuario || ' ' || users.appusuario || ' ' || users.apmusuario) LIKE ?", ["%" . strtoupper($keyword) . "%"]);
            })
            ->addColumn('btn_v', function ($record) {
                return view('buttons.button_viatico', [
                    'comision' => $record,
                ])->render();
            })
            ->addColumn('btn_vigencia', 'buttons.button_vigencia')
            ->rawColumns(['btn_vigencia', 'btn_v'])
            ->orderColumn('comision', 'comisiones.comision $1')
            ->toJson();
    }
    public function validateComition()
    {
        $user = Auth::user();

        $statusComision = StatusComision::where('idtype', $user->idtype)->get();

        $direccionesACargo = is_array($user->direcciones)
            ? $user->direcciones
            : json_decode($user->direcciones, true);

        $direccionesACargo = array_filter(array_map('trim', (array)$direccionesACargo));

        $allObjetives = Objetive::all();

        $filteredObjetives = $allObjetives->filter(function ($objetive) use ($direccionesACargo) {

            if (empty($objetive->ur)) {
                return false;
            }

            $urObjetivoArray = is_array($objetive->ur)
                ? $objetive->ur
                : json_decode($objetive->ur, true);

            if (!is_array($urObjetivoArray)) {
                return false;
            }

            $urObjetivoArray = array_filter(array_map('trim', $urObjetivoArray));

            return count(array_intersect($direccionesACargo, $urObjetivoArray)) > 0;
        })->values();

        DB::table('registros')->insert([
            'movimiento' => 'HA CONSULTADO LAS COMISIONES',
            'id_user' => $user->id_user,
            'fecha_movimiento' => now()
        ]);

        return view('comision.validateComition', [
            'statusComision' => $statusComision,
            'filteredObjetives' => $filteredObjetives,
            'userType' => $user->idtype
        ]);
    }

    public function autoEmpleados(Request $request)
    {
        $listado = [];
        $query  = User::all();
        foreach ($query as $emp) {
            $numEmpleado = $emp->numempleado;
            $nombre         = $emp->nomusuario . ' ' . $emp->appusuario . ' ' . $emp->apmusuario;
            array_push($listado, $numEmpleado . " - " . $nombre);
        }
        return $listado;
    }

    public function datosUnEmpleado(Request $request)
    {
        $porciones = explode("-", $request->empleado);
        $emp = DB::connection('oracle_primary')->table('users')
            ->select(
                'users.nomusuario as nomusuario',
                'users.appusuario as appusuario',
                'users.apmusuario as apmusuario',
                'users.numempleado as numempleado',
                'users.email as email'
            )
            ->where('users.unidad', '!=', Session::get('ur'))
            ->where('users.numempleado', '=', trim($porciones[0]))
            ->get();
        return $emp[0]->numempleado . '|' . $emp[0]->email;
    }

    private function getComisionData(int $idComision): array
    {
        $query = DB::connection('oracle_primary')->table('comisiones')
            ->select(
                'comisiones.idcomision as idcomision',
                'comisiones.comision as comision',
                'comisiones.folio as folio',
                'comisiones.clc as clc',
                'comisiones.importe as importe',
                'comisiones.yearcomision as yearcomision',
                'comisiones.initialdate as initialdate',
                'comisiones.partidapasaje as partidapasaje',
                'comisiones.partidaviatico as partidaviatico',
                'comisiones.lugar as lugar',
                'comisiones.destino as destino',
                'comisiones.origen as origen',
                'comisiones.oficio as oficio',
                'comisiones.dayscompletes as dayscompletes',
                'comisiones.hours as hours',
                'comisiones.placas as placas',
                'comisiones.nota as nota',
                'comisiones.status as status',
                'comisiones.deposito as deposito',
                'comisiones.notarevisor as notarevisor',
                'comisiones.nivelcomision as nivelcomision',
                'comisiones.puestoComision as puestocomision',
                'comisiones.dateAutorized as dateautorized',
                'comisiones.dateComprobation as datecomprobation',
                'comisiones.justificacion as justificacion',
                'comisiones.documentacionfisica as documentacionfisica',
                'comisiones.finaldate as finaldate',
                'comisiones.autorizacion as autorizacion',
                'comisiones.validador as validador',
                'comisiones.rev1_fecha as rev1_fecha',
                'comisiones.rev2_fecha as rev2_fecha',
                DB::raw("users.nomusuario || ' ' || users.appusuario || ' ' || users.apmusuario AS nombre_completo"),
                'personcomision.cargo as cargo',
                'users.contacto as contacto',
                'users.unidad as unidad',
                'users.numempleado as numempleado',
                'users.importcomplete as importcomplete',
                'users.email as email',
                'users.id_user as id_user',
                'statuscomision.statuscomision as statuscomision',
                'statuscomision.idstatuscomision as idstatuscomision',
                'typecomision.idtypecomision as idtypecomision',
                'typecomision.typecomision as typecomision',
                'modalidades.modalidad as modalidad',
                'modalidades.acronym as acronym',
                'modalidades.idmodalidad as idmodalidad',
                'personcomision.adscripcion as adscripcion',
                'personcomision.direccion as direccion',
                'personcomision.rfc as rfc',
                'personcomision.curp as curp',
                'personcomision.nivel as nivel',
                'personcomision.banco as banco',
                'personcomision.clabe as clabe',
                'personcomision.cuenta as cuenta',
                'personcomision.cargo as cargo_comisionado',
                'transports.transport as transport',
                'reasignaciones.returnimport as returnimport',
                'reasignaciones.motivo as motivo',
                'reasignaciones.comprobante as comprobante',
                'reasignaciones.motivoampliacion as motivoampliacion',
                'reasignaciones.datereasignacioninicial as datereasignacioninicial',
                'reasignaciones.created_at as created_at',
                'reasignaciones.datereasignacionfinal as datereasignacionfinal',
                'reasignaciones.datereturn as datereturn',
                'reasignaciones.newimport as newimport',
                'reasignaciones.idreasignacion as idreasignacion',
                'objetives.objetive as objetive',
                'tipodeposito.tipodeposito as tipodeposito',
                'tiporeintegro.tiporeintegro as tiporeintegro',
                'tiporeintegro.acronymreintegro as acronymreintegro',
                'reintegros.idreintegro as idreintegro',
                'reintegros.monto as monto_reintegro',
                'reintegros.cancelacion as cancelacion_reintegro',
                'reintegros.comprobante as comprobante_reintegro',
                'direcciones.nombre_ur as nombre_ur',
                'imports.completeimport as completeimport',
                'imports.mediumimport as mediumimport'
            )
            ->leftJoin('personcomision', 'personcomision.idpersoncomision', '=', 'comisiones.idpersoncomision')
            ->leftJoin('users', 'users.id_user', '=', 'personcomision.id_user')
            ->leftJoin('transports', 'transports.id_transport', '=', 'comisiones.id_transport')
            ->leftJoin('statuscomision', 'statuscomision.idstatuscomision', '=', 'comisiones.idstatuscomision')
            ->leftJoin('typecomision', 'typecomision.idtypecomision', '=', 'comisiones.idtypecomision')
            ->leftJoin('modalidades', 'modalidades.idmodalidad', '=', 'comisiones.idmodalidad')
            ->leftJoin('reasignaciones', 'reasignaciones.idcomision', '=', 'comisiones.idcomision')
            ->leftJoin('objetives', 'objetives.idobjetive', '=', 'comisiones.idobjetive')
            ->leftJoin('reintegros', 'comisiones.idcomision', '=', 'reintegros.idcomision')
            ->leftJoin('tiporeintegro', 'tiporeintegro.idtiporeintegro', '=', 'reintegros.idtiporeintegro')
            ->leftJoin('tipodeposito', 'tipodeposito.iddeposito', '=', 'reintegros.iddeposito')
            ->leftJoin('imports', 'imports.personnivel', '=', 'personcomision.nivel')
            ->leftJoin('direcciones', 'comisiones.oficio', '=', 'direcciones.no_direccion')
            ->where('comisiones.idcomision', '=', $idComision)
            ->first();

        if (!$query) {
            return [
                'query' => null,
                'validador' => null,
                'contacto' => null,
                'reasignaciones' => collect(),
                'comprobaciones' => collect(),
                'gastos' => collect()
            ];
        }
        $montoCompleto = null;

        if (!empty($query->placas) && $query->placas > 0) {
            $montoCompleto = $query->placas;
        } elseif (!empty($query->importcomplete) && $query->importcomplete > 0) {
            $montoCompleto = $query->importcomplete;
        }

        if ($montoCompleto) {
            $importesCalculados = (object) [
                'completo' => $montoCompleto,
                'medio'    => $montoCompleto / 2
            ];
        } else {
            $importesCalculados = $this->calcularImportes($query->nivel);
        }
        $gastos = GastosSinComprobante::where('idcomision', $idComision)
            ->where('activo', '=', '1')
            ->orderBy('datepay', 'asc')
            ->get();

        $comprobaciones = Factura::where('idcomision', $idComision)
            ->where('cancelacion', '=', 2)
            ->get()
            ->groupBy('idtypefactura');
        $validador = null;
        $cargoData = null;
        $contact = null;
        if ($query->validador) {
            $validadorNum = $query->validador;
            $validador = DB::connection('oracle_secondary')
                ->table('EMPLEADOS_VIATICOS')
                ->where('NUM_EMPLEADO', '=', $validadorNum)
                ->orderBy('NUM_EMPLEADO', 'desc')
                ->first();

            if (!$validador) {
                $validador = DB::connection('oracle_primary')
                    ->table('users')
                    ->select(
                        DB::raw(
                            "users.appusuario || ' ' || users.apmusuario || ' ' || users.nomusuario AS nombre_completo"
                        ),
                        'users.contacto as contacto',
                        'users.cargo as cargo',
                        'users.unidad as unidad',
                        'users.id_user as id_user',
                        'users.numempleado as numempleado'
                    )
                    ->leftJoin(
                        'personcomision',
                        'personcomision.id_user',
                        '=',
                        'users.id_user'
                    )
                    ->where(function ($query) use ($validadorNum) {
                        $query->where('users.numempleado', $validadorNum)
                            ->orWhereExists(function ($historyQuery) use ($validadorNum) {
                                $historyQuery->select(DB::raw('1'))
                                    ->from('historialEmployee')
                                    ->whereColumn(
                                        'historialEmployee.id_user',
                                        'users.id_user'
                                    )
                                    ->where(function ($numberQuery) use ($validadorNum) {
                                        $numberQuery
                                            ->where('historialEmployee.numero_anterior', $validadorNum)
                                            ->orWhere('historialEmployee.numero_nuevo', $validadorNum);
                                    });
                            });
                    })
                    // Si hay coincidencia actual e histórica, prioriza el número actual.
                    ->orderByRaw(
                        'CASE WHEN users.numempleado = ? THEN 0 ELSE 1 END',
                        [$validadorNum]
                    )
                    ->first();
                if ($validador) {
                    $cargoData = DB::connection('oracle_secondary')
                        ->table('EMPLEADOS_VIATICOS')
                        ->select('CARGO', 'NIVEL')
                        // ->where('NIVEL', '=', $validador->cargo)
                        // ->where('UNIDAD', '=', $validador->unidad)
                        ->where('NUM_EMPLEADO', '=', $validador->numempleado)
                        ->first();
                }
            }
        }
        // $validador = DB::connection('oracle_primary') // Usamos tu conexión de Oracle
        //     ->table('users')
        //     ->join('personcomision', 'users.id_user', '=', 'personcomision.id_user')
        //     ->where('users.numempleado', $query->validador)
        //     ->select('personcomision.cargo as cargo', 'users.appusuario', 'users.apmusuario', 'users.nomusuario', 'users.contacto')
        //     ->first();
        // dd($validador);
        $reasignaciones = DB::connection('oracle_primary')->table('reasignaciones')
            ->where('idcomision', $idComision)
            ->orderBy('created_at', 'desc')
            ->get();

        $gastos = GastosSinComprobante::where('idcomision', $idComision)
            ->where('activo', '=', '1')
            ->orderBy('datepay', 'asc')
            ->get();

        $facturasAceptadas = Factura::where('idcomision', $idComision)
            ->where('cancelacion', '=', 2)
            ->get();

        $totalFacturasAceptadas = $facturasAceptadas->sum('importe');

        $comprobaciones = $facturasAceptadas->groupBy('idtypefactura');

        $totalGastosSinC = $gastos->sum('monto');


        return [
            'query' => $query,
            'validador' => $validador,
            'cargoData' => $cargoData,
            'contacto' => $contact,
            'reasignaciones' => $reasignaciones,
            'comprobaciones' => $comprobaciones,
            'gastos' => $gastos,
            'totalFacturas' => $totalFacturasAceptadas,
            'totalGeneral'  => $totalFacturasAceptadas + $totalGastosSinC,
            'importes_ley' => $importesCalculados
        ];
    }
    public function generateSingleReasignacionPdf(int $idComision, int $idReasignacion)
    {
        $data = $this->getComisionData($idComision);
        if (!$data['query']) {
            return redirect()->back()->with('error', 'Comisión no encontrada.');
        }
        $reasignacion = $data['reasignaciones']->firstWhere('idreasignacion', $idReasignacion);
        if (!$reasignacion) {
            return redirect()->back()->with('error', 'Reasignación no encontrada para esta comisión.');
        }
        $pdf = PDF::loadView('comision.ampliacionPDF', [
            'query' => $data['query'],
            'validador' => $data['validador'],
            'reasignacion' => $reasignacion,
        ]);
        return $pdf->stream('reporte_reasignacion_' . $reasignacion->idreasignacion . '_comision_' . $data['query']->comision . '.pdf');
    }

    public function generateAllReasignacionesPdfZip(int $idComision)
    {
        $data = $this->getComisionData($idComision);
        if (!$data['query']) {
            return redirect()->back()->with('error', 'Comisión no encontrada.');
        }
        $reasignaciones = $data['reasignaciones'];
        if ($reasignaciones->isEmpty()) {
            return redirect()->back()->with('info', 'No hay reasignaciones para esta comisión.');
        }
        $zipFileName = 'reasignaciones_comision_' . $data['query']->comision . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($reasignaciones as $reasignacion) {
                $pdf = PDF::loadView('pdfs.reasignacion_report', [
                    'query' => $data['query'],
                    'validador' => $data['validador'],
                    'reasignacion' => $reasignacion,
                ]);
                $pdfContent = $pdf->output();
                $pdfFileName = 'reasignacion_' . $reasignacion->idreasignacion . '_comision_' . $data['query']->comision . '.pdf';
                $zip->addFromString($pdfFileName, $pdfContent);
            }
            $zip->close();
            return response()->download($zipPath)->deleteFileAfterSend(true);
        } else {
            return redirect()->back()->with('error', 'No se pudo crear el archivo ZIP.');
        }
    }

    public function solicitudPDF($idComisionHasheado)
    {
        try {
            $idComision = Crypt::decryptString($idComisionHasheado);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'El identificador de la comisión es inválido.');
        }

        $data = $this->getComisionData($idComision);

        if (!$data['query']) {
            return redirect()->back()->with('error', 'Comisión no encontrada para generar el PDF de solicitud.');
        }
        $pdf = PDF::loadView('comision.solicitudPDF', $data);

        $arrayid = (array) $idComision;
        DB::table('registros')->insert([
            'movimiento' => "HA CONSULTADO EL OFICIO DE SOLICITUD DE LA COMISIÓN: ",
            'id_user' => Auth::user()->id_user,
            'fecha_movimiento' => now(),
            'created_at' => date('Y-m-d-H:i:s'),
            'idcomision' => json_encode($arrayid)
        ]);
        return $pdf->stream('solicitud_comision_' . $data['query']->comision . '.pdf');
    }
    public function acusePDF($idComisionHasheado)
    {
        try {
            $idComision = Crypt::decryptString($idComisionHasheado);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'El identificador de la comisión es inválido.');
        }
        $data = $this->getComisionData($idComision);

        if (!$data['query']) {
            return redirect()->back()->with('error', 'Comisión no encontrada para generar el PDF de acuse.');
        }
        $comision = $data['query'];
        $viatico = $comision->acronym . "_" . $comision->comision . "_" . $comision->yearcomision;
        $pdf = PDF::loadView('comision.acusePdf', $data);
        $arrayid = (array) $idComision;
        DB::table('registros')->insert([
            'movimiento' => "HA CONSULTADO EL OFICIO DE LA COMISIÓN: ",
            'id_user' => Auth::user()->id_user,
            'fecha_movimiento' => now(),
            'created_at' => date('Y-m-d-H:i:s'),
            'idcomision' => json_encode($arrayid)
        ]);
        return $pdf->stream('acuse_comision_' . $comision->comision . '.pdf');
    }
    public function formatoComprobacion($idComisionHasheado)
    {
        try {
            $idComision = Crypt::decryptString($idComisionHasheado);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'El identificador de la comisión es inválido.');
        }
        $data = $this->getComisionData($idComision);

        if (!$data['query']) {
            return redirect()->back()->with('error', 'Comisión no encontrada para generar el PDF de solicitud.');
        }
        $comision = $data['query'];

        $viatico = $comision->acronym . "_" . $comision->comision . "_" . $comision->yearcomision;
        $pdf = PDF::loadView('comision.formatoComprobacion', $data);

        $arrayid = (array) $idComision;
        DB::table('registros')->insert([
            'movimiento' => "HA CONSULTADO EL FORMATO DE COMPROBACIÓN DE LA COMISIÓN: ",
            'id_user' => Auth::user()->id_user,
            'fecha_movimiento' => now(),
            'created_at' => date('Y-m-d-H:i:s'),
            'idcomision' => json_encode($arrayid)
        ]);

        return $pdf->stream('Formato_Comprobacion' . $data['query']->comision . '.pdf');
    }

    public function generarComision(): int
    {
        return Cache::lock('lock:comision_generation', 10)->block(5, function () {
            $currentYear = date('Y');

            $ultimoFolioActualAnio = Comision::where('yearcomision', $currentYear)
                ->orderBy('comision', 'desc')
                ->first(['comision', 'yearcomision']);

            $comision = 1;

            if ($ultimoFolioActualAnio) {
                $comision = $ultimoFolioActualAnio->comision + 1;
            }

            while (Comision::where('yearcomision', $currentYear)
                ->where('comision', $comision)
                ->exists()
            ) {
                $comision++;
            }

            return $comision;
        });
    }

    public function validateComission(Request $request)
    {
        $status = $request->get('autorizacion');
        $idComisiones = (array) $request->get('idcomision');

        $rules = [
            'idcomision'   => 'required|array',
            'idcomision.*' => 'integer',
            'autorizacion' => 'required|integer',
        ];

        if (in_array($status, [2, 17])) {
            $rules['idobjetive'] = 'required|integer';
            $rules['importe']    = 'required|numeric';
        } elseif (in_array($status, [3, 18])) {
            $rules['justificacion'] = ['required', 'string', 'min:20', 'regex:/^(?!.*(.)\1{4,}).*[\pL\s]{5,}/u'];
        } else {
            return redirect('validateComition')->with('error', 'Estado de autorización no válido.');
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('validateComition')->withErrors($validator)->withInput();
        }

        $justificacion = strtoupper($request->get('justificacion'));
        $autorizacion = in_array($status, [2, 17]) ? '1' : '0';
        $viaticosProcesados = [];

        foreach ($idComisiones as $id) {
            $comision = Comision::find($id);
            if (!$comision) continue;

            $idobjetive = in_array($status, [2, 17]) ? $request->get('idobjetive') : null;
            $importe = $request->get('importe');
            $comisionNumber = $comision->comision;

            if (in_array($status, [2, 17])) {
                if ($comision->comision === null) {
                    $comisionNumber = $this->generarComision();
                }
            } else {
                $comisionNumber = null;
            }

            DB::connection('oracle_primary')->table('comisiones')->where('idcomision', $id)
                ->update([
                    'idstatuscomision' => $status,
                    'idobjetive'       => $idobjetive,
                    'autorizacion'     => $autorizacion,
                    'importe'          => $importe,
                    'justificacion'    => $justificacion,
                    'comision'         => $comisionNumber
                ]);

            $acronym = DB::connection('oracle_primary')->table('modalidades')
                ->where('idmodalidad', $comision->idmodalidad)
                ->value('acronym') ?? 'S/A';

            $viaticosProcesados[] = $acronym . "_" . ($comisionNumber ?? '0') . "_" . $comision->yearcomision;
        }

        DB::table('registros')->insert([
            'movimiento'       => "HA VALIDADO Y CAMBIADO EL STATUS A LAS SIGUIENTES COMISIONES:",
            'id_user'          => Auth::user()->id_user,
            'idstatus'         => $status,
            'idcomision'       => json_encode($idComisiones),
            'fecha_movimiento' => now()
        ]);

        Session::flash('success', 'Se han procesado ' . count($idComisiones) . ' comisiones correctamente.');
        return redirect('validateComition');
    }

    public function editComision($idComisionHasheado)
    {
        try {
            $idComision = Crypt::decryptString($idComisionHasheado);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'El identificador de la comisión es inválido o ha sido alterado.');
        }

        $data = $this->getComisionData($idComision);
        $query = $data['query'];
        $user = $data['contacto'];

        if (!$query) {
            return redirect()->back()->with('error', 'Comisión no encontrada');
        }
        $data = $this->getComisionData($idComision);
        $importes_ley = $data['importes_ley'];

        $validador = $data['validador'];
        $contact = $data['contacto'];
        $reasignaciones = $data['reasignaciones'];
        $comprobaciones = $data['comprobaciones'];
        $idModalidad = $query->idmodalidad;

        $statusComision = StatusComision::where('idModalidad', '=', $idModalidad)
            ->where('idtype', Auth::user()->idtype)
            ->get();

        $tipoDepositos = DB::connection('oracle_primary')->table('tipodeposito')->get();

        $gastos = GastosSinComprobante::where('idcomision', $idComision)
            ->orderBy('datepay', 'asc')
            ->get();
        $arrayid = (array) $idComision;
        $modalidades = DB::table('modalidades')->get();
        $tipoComisiones = DB::table('typecomision')->get();
        return view('comision.editComision', compact(
            'query',
            'validador',
            'contact',
            'gastos',
            'reasignaciones',
            'comprobaciones',
            'tipoDepositos',
            'idComision',
            'modalidades',
            'statusComision',
            'importes_ley',
            'tipoComisiones'
        ));
        // return view('comision.editComision', compact(['query', 'statusComision', 'import', 'reasignaciones', 'validador', 'historialAmpliaciones']));
    }

    public function updateComision(Request $request)
    {
        $request->validate([
            'idcomision'          => 'required|integer|exists:comisiones,idcomision',
            'idstatuscomision'    => 'required|integer|exists:statuscomision,idstatuscomision',
            'monto_reintegro'     => 'nullable|string',
            'documentacionfisica' => 'nullable',
            'clc'                 => 'nullable|string|max:255',
            'deposito'            => 'nullable|string|max:255',
            'status'              => 'nullable|string|max:255',
            'notarevisor'         => 'nullable|string|max:3000',
            'validador'           => 'sometimes|nullable|string|max:255',
        ]);

        if (
            $request->exists('validador') &&
            (int) Auth::user()->idtype !== 1
        ) {
            abort(403, 'No tienes permiso para cambiar el autorizador.');
        }

        $idcomision = $request->input('idcomision');
        $status = $request->input('status');
        $newStatus = (int) $request->input('idstatuscomision');

        $comision = Comision::with('personComision.user')
            ->find($idcomision);

        if (!$comision) {
            return back()->withErrors([
                'error' => 'Comisión no encontrada.',
            ]);
        }

        $monto = $request->input('monto_reintegro');

        if ($monto !== null && trim($monto) !== '') {
            $montoLimpio = str_replace(['$', ','], '', $monto);

            if (!is_numeric($montoLimpio)) {
                return back()->withErrors([
                    'monto_reintegro' => 'El monto de reintegro no es válido.',
                ]);
            }

            $montoFinal = number_format((float) $montoLimpio, 2, '.', '');

            DB::connection('oracle_primary')
                ->table('reintegros')
                ->where('idcomision', $idcomision)
                ->update([
                    'monto' => $montoFinal,
                ]);
        }

        $facturasPendientes = DB::connection('oracle_primary')
            ->table('facturas')
            ->where('idcomision', $idcomision)
            ->where('cancelacion', 0)
            ->exists();

        if ($facturasPendientes) {
            return back()->withErrors([
                'error' => 'No se puede cambiar el estatus: Existen facturas pendientes de revisar.',
            ]);
        }

        $idStatusComprobacion = [13, 22, 25, 26];

        $datecomprobation = in_array($newStatus, $idStatusComprobacion, true)
            ? DB::raw(
                "TO_DATE('" . date('d-m-Y H:i:s') .
                    "', 'DD-MM-YYYY HH24:MI:SS')"
            )
            : null;

        $nowOracle = DB::raw(
            "TO_DATE('" . date('d-m-Y H:i:s') .
                "', 'DD-MM-YYYY HH24:MI:SS')"
        );

        $rev1Estatus = $comision->rev1_estatus;
        $rev1Fecha = $comision->rev1_fecha;
        $rev2Estatus = $comision->rev2_estatus;
        $rev2Fecha = $comision->rev2_fecha;

        $nota = $request->filled('notarevisor')
            ? $request->input('notarevisor')
            : $comision->notarevisor;

        if (in_array($newStatus, [13, 22, 241, 242], true)) {
            if (is_null($rev1Estatus)) {
                $rev1Estatus = 1;
                $rev1Fecha = $nowOracle;
            } elseif (is_null($rev2Estatus)) {
                $rev2Estatus = 1;
                $rev2Fecha = $nowOracle;
            }
        }

        $updateData = [
            'idstatuscomision'    => $newStatus,
            'documentacionfisica' => $request->input('documentacionfisica'),
            'clc'                 => $request->input('clc'),
            'deposito'            => $request->input('deposito'),
            'datecomprobation'    => $datecomprobation,
            'status'              => $status,
            'notarevisor'         => $nota,
            'rev1_estatus'        => $rev1Estatus,
            'rev1_fecha'          => $rev1Fecha,
            'rev2_estatus'        => $rev2Estatus,
            'rev2_fecha'          => $rev2Fecha,
        ];

        if ($request->exists('validador')) {
            $updateData['validador'] = $request->input('validador');
        }

        DB::connection('oracle_primary')
            ->table('comisiones')
            ->where('idcomision', $idcomision)
            ->update($updateData);

        $acronym = DB::connection('oracle_primary')
            ->table('modalidades')
            ->where('idmodalidad', $comision->idmodalidad)
            ->value('acronym') ?? 'S/A';

        $viatico = $acronym . '_' . $comision->comision . '_' . $comision->yearcomision;
        $arrayid = (array) $idcomision;

        DB::table('registros')->insert([
            'movimiento' => "HA VALIDADO Y CAMBIADO EL ESTATUS LA SIGUIENTE COMISIÓN: {$viatico}",
            'id_user' => Auth::user()->id_user,
            'fecha_movimiento' => now(),
            'created_at' => date('Y-m-d H:i:s'),
            'idcomision' => json_encode($arrayid),
            'idstatus' => $newStatus,
        ]);

        return back()->with('success', 'Comisión actualizada y registrada exitosamente.');
    }

    public function reasignacion_list_json($idComision)
    {
        $query = DB::connection('oracle_primary')->table('reasignaciones')
            ->select(
                'reasignaciones.idreasignacion as idreasignacion',
                'reasignaciones.datereasignacioninicial as datereasignacioninicial',
                'reasignaciones.datereasignacionfinal as datereasignacionfinal',
                'reasignaciones.datereturn as datereturn',
                'reasignaciones.motivo as motivo',
                'reasignaciones.newimport as newimport',
                'reasignaciones.returnimport as returnimport',
                'reasignaciones.idcomision as idcomision_reasignacion',
                'comisiones.comision as folio_comision',
                'comisiones.importe as importe',
                'comisiones.initialdate as initialdate_comision',
                'comisiones.comision as comision',
                'comisiones.finaldate as finaldate_comision',
                DB::raw("users.nomusuario || ' ' || users.appusuario || ' ' || users.apmusuario as nombre_completo_comisionado"),
                'personcomision.cargo as cargo_comisionado',
                'statuscomision.statuscomision as statuscomision',
                'typecomision.typecomision as tipo_comision'
            )
            ->leftJoin('comisiones', 'comisiones.idcomision', '=', 'reasignaciones.idcomision')
            ->leftJoin('personcomision', 'personcomision.idpersoncomision', '=', 'comisiones.idpersoncomision')
            ->leftJoin('users', 'users.id_user', '=', 'personcomision.id_user')
            ->leftJoin('statuscomision', 'statuscomision.idstatuscomision', '=', 'comisiones.idstatuscomision')
            ->leftJoin('typecomision', 'typecomision.idtypecomision', '=', 'comisiones.idtypecomision')
            ->where('reasignaciones.idcomision', $idComision)
            ->orderBy('reasignaciones.idreasignacion', 'desc');
        if (Auth::check()) {
            if (Auth::user()->idtype == 3) {
                $query->where('users.id_user', '=', Auth::user()->id_user);
            }
            if (Auth::user()->idtype == 2) {
                $query->where('comisiones.validador', '=', Auth::user()->numempleado);
            }
        }
        $jsonData = DataTables::of($query)
            ->filterColumn('nombre_completo_comisionado', function ($query, $keyword) {
                $query->whereRaw("upper(users.nomusuario || ' ' || users.appusuario || ' ' || users.apmusuario) LIKE ?", ["%" . strtoupper($keyword) . "%"]);
            })
            ->addColumn('action_buttons', function ($reasignacion) {
                $pdfButton = '<a href="' . route('generateSingleReasignacionPdf', ['idComision' => $reasignacion->idcomision_reasignacion, 'idAmpliacion' => $reasignacion->idreasignacion]) . '" class="btn btn-sm btn-primary" target="_blank">Ver PDF</a>';
                return $pdfButton;
            })
            ->rawColumns(['action_buttons'])
            ->toJson();
        return $jsonData;
    }

    public function aprobarMasivo(Request $request)
    {
        $request->validate([
            'comision_ids' => 'required|array|min:1',
            // 'idobjetive' => 'required|integer',
        ]);

        $comisionIds = $request->input('comision_ids');
        // $idobjetive = $request->input('idobjetive');
        $userNumEmpleado = Auth::user()->numempleado;

        try {
            DB::connection('oracle_primary')->beginTransaction();

            $comisiones = Comision::on('oracle_primary')
                ->whereIn('idcomision', $comisionIds)
                ->where('validador', $userNumEmpleado)
                ->with(['personComision.user', 'modalidad'])
                ->get();

            if ($comisiones->count() !== count($comisionIds) && Auth::user()->idtype == 2) {
                DB::connection('oracle_primary')->rollBack();
                return response()->json([
                    'message' => 'No tienes permisos sobre algunas de las comisiones seleccionadas.'
                ], 403);
            }

            if ($comisiones->isEmpty()) {
                DB::connection('oracle_primary')->rollBack();
                return response()->json(['message' => 'No se encontraron comisiones para aprobar.'], 404);
            }

            $yaAutorizadas = $comisiones->where('autorizacion', 1);
            if ($yaAutorizadas->isNotEmpty()) {
                DB::connection('oracle_primary')->rollBack();
                return response()->json([
                    'message' => 'Operación cancelada. Una o más comisiones seleccionadas ya fueron autorizadas previamente.',
                    'id_comisiones_autorizadas' => $yaAutorizadas->pluck('idcomision')->toArray()
                ], 422);
            }

            $idstatuscomision = null;

            Cache::lock('lock:comision_assignment', 30)->block(20, function () use ($comisiones, &$idstatuscomision) {
                $year = date('Y');

                $maxFolio = Comision::on('oracle_primary')
                    ->whereYear('created_at', $year)
                    ->whereNotNull('comision')
                    ->whereRaw("REGEXP_LIKE(comision, '^[0-9]+$')")
                    ->max(DB::raw('TO_NUMBER(comision)'));

                $currentNumero = $maxFolio ? (int)$maxFolio : 0;

                foreach ($comisiones as $comision) {
                    $currentNumero++;
                    $idModalidad = $comision->idmodalidad;

                    if ($idModalidad == 1) {
                        $idstatuscomision = 2;
                    } elseif ($idModalidad == 2) {
                        $idstatuscomision = 17;
                    }

                    if ($idstatuscomision !== null) {
                        DB::connection('oracle_primary')
                            ->table('comisiones')
                            ->where('idcomision', $comision->idcomision)
                            ->update([
                                'idstatuscomision' => $idstatuscomision,
                                // 'idobjetive' => $idobjetive,
                                'autorizacion' => 1,
                                'dateautorized' => DB::raw("TO_DATE('" . date('d-m-Y H:i:s') . "', 'DD-MM-YYYY HH24:MI:SS')"),
                                'comision' => $currentNumero
                            ]);

                        $comision->comision = $currentNumero;
                        $comision->idstatuscomision = $idstatuscomision;
                    }
                }
            });

            DB::connection('oracle_primary')->commit();

            $modalidadLote = $comisiones->first()->idmodalidad ?? null;
            $comisionesPorPersona = $comisiones->groupBy('personComision.user.email');

            $claseMail = NotificationStatusMail::class;
            if ($modalidadLote == 2) {
                $claseMail = NotificacionDevengada::class;
            }

            foreach ($comisionesPorPersona as $emailDestinatario => $comisionesDelUsuario) {
                if (!empty($emailDestinatario)) {
                    Mail::to($emailDestinatario)->queue(
                        new $claseMail($comisionesDelUsuario->toArray(), 'aprobacion')
                    );
                }
            }

            DB::table('registros')->insert([
                'movimiento' => "HA VALIDADO Y CAMBIADO DE STATUS A APROBADO LAS COMISIONES SELECCIONADAS",
                'id_user' => Auth::user()->id_user,
                'fecha_movimiento' => now(),
                'created_at' => date('Y-m-d H:i:s'),
                'idcomision' => json_encode($comisionIds),
                'idstatus' => $idstatuscomision
            ]);

            return response()->json(['message' => 'Las comisiones seleccionadas han sido aprobadas y los correos enviados.']);
        } catch (\Exception $e) {
            DB::connection('oracle_primary')->rollBack();
            return response()->json(['message' => 'Ocurrió un error al aprobar las comisiones: ' . $e->getMessage()], 500);
        }
    }

    public function cancelarMasivo(Request $request)
    {
        $request->validate([
            'comision_ids' => 'required|array|min:1',
            'justificacion' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?!.*(.)\1{4,}).*[\pL\s]{5,}/u',
            ],
        ]);

        $comisionIds = $request->input('comision_ids');
        $justificacion = strtoupper($request->input('justificacion'));
        $userNumEmpleado = Auth::user()->numempleado;

        try {
            DB::connection('oracle_primary')->beginTransaction();

            $comisiones = Comision::on('oracle_primary')
                ->whereIn('idcomision', $comisionIds)
                ->where('validador', $userNumEmpleado)
                ->with(['personComision.user', 'modalidad'])
                ->get();

            if ($comisiones->count() !== count($comisionIds) && Auth::user()->idtype == 2) {
                DB::connection('oracle_primary')->rollBack();
                return response()->json(['message' => 'No tienes permisos para cancelar algunas de las comisiones. El comisionado seleccionó a un autorizador distinto'], 403);
            }

            if ($comisiones->isEmpty()) {
                DB::connection('oracle_primary')->rollBack();
                return response()->json(['message' => 'No se encontraron comisiones.'], 404);
            }
            DB::connection('oracle_primary')
                ->table('comisiones')
                ->whereIn('idcomision', $comisionIds)
                ->update([
                    'justificacion' => $justificacion,
                    'idstatuscomision' => DB::raw("
                    CASE idmodalidad
                        WHEN 1 THEN 3
                        WHEN 2 THEN 18
                        ELSE idstatuscomision
                    END
                ")
                ]);

            DB::connection('oracle_primary')->commit();

            $comisionesPorPersona = $comisiones->groupBy('personComision.user.email');
            foreach ($comisionesPorPersona as $emailDestinatario => $comisionesDelUsuario) {
                if (!empty($emailDestinatario)) {
                    Mail::to($emailDestinatario)->queue(
                        new NotificationStatusMail($comisionesDelUsuario->toArray(), 'cancelacion', $justificacion)
                    );
                }
            }

            DB::table('registros')->insert([
                'movimiento' => "HA CANCELADO LAS SIGUIENTES COMISIONES:",
                'id_user' => Auth::user()->id_user,
                'fecha_movimiento' => now(),
                'created_at' => date('Y-m-d H:i:s'),
                'idcomision' => json_encode($comisionIds),
                'idstatus' => 3
            ]);

            return response()->json(['message' => 'Comisiones canceladas exitosamente.']);
        } catch (\Exception $e) {
            DB::connection('oracle_primary')->rollBack();
            return response()->json(['message' => 'Error al cancelar: ' . $e->getMessage()], 500);
        }
    }

    public function editarMasivo(Request $request)
    {
        $request->validate([
            'comision_ids' => 'required|array',
            'comision_ids.*' => 'exists:comisiones,idcomision',
            'idstatuscomision' => 'nullable|exists:statuscomision,idstatuscomision',
            'clc' => 'nullable|string|max:255',
            'documentacionFisica' => 'nullable',
            'importComisionado' => 'nullable|numeric|min:0',
            'importComision' => 'nullable|numeric|min:0',
            'nota' => 'nullable|string|max:3000',
            'oficio' => 'nullable|string|max:255',
            'deposito' => 'nullable|string|max:255',
            'dateReasignacionFinal' => [
                'required_if:idstatuscomision,11',
                'required_if:idstatuscomision,14',
                'date',
            ],
            'motivoAmpliacion' => [
                'required_if:idstatuscomision,11',
                'required_if:idstatuscomision,14',
                'string',
                'max:255',
            ],
        ], [
            'comision_ids.required' => 'Debe seleccionar al menos una comisión.',
            'comision_ids.*.exists' => 'Una o más comisiones seleccionadas no son válidas.',
            'nota.max' => 'La nota no debe exceder de los 3000 caracteres.',
        ]);

        $hasValue = function ($key) use ($request) {
            if (!$request->has($key)) {
                return false;
            }

            $value = $request->input($key);

            return $value !== null && trim((string) $value) !== '';
        };

        $updateDataGeneral = [];

        if ($hasValue('clc')) {
            $updateDataGeneral['clc'] = $request->input('clc');
        }

        if ($hasValue('nota')) {
            $updateDataGeneral['nota'] = $request->input('nota');
        }

        if ($hasValue('oficio')) {
            $updateDataGeneral['oficio'] = $request->input('oficio');
        }

        if ($hasValue('importComisionado')) {
            $updateDataGeneral['placas'] = $request->input('importComisionado');
        }

        if ($hasValue('importComision')) {
            $updateDataGeneral['import_comision'] = $request->input('importComision');
        }

        if ($hasValue('documentacionFisica')) {
            $updateDataGeneral['documentacionfisica'] = $request->input('documentacionFisica');
        }

        if ($hasValue('deposito')) {
            $updateDataGeneral['deposito'] = $request->input('deposito');
        }

        $isAmpliacion = (string) $request->input('idstatuscomision') === '11';
        $isReduccionAnticipada = (string) $request->input('idstatuscomision') === '14';
        $isPaymentStatus = in_array(
            (string) $request->input('idstatuscomision'),
            ['8', '24'],
            true
        );
        $isReintegroStatus = in_array(
            (string) $request->input('idstatuscomision'),
            ['3', '14'],
            true
        );

        $idStatusComprobacion = [13, 22, 25, 26];

        $commissionsForMail = [];
        $commissionsForReintegroMail = [];
        $commissionsForAmpliacionMail = [];

        try {
            DB::beginTransaction();

            $comisiones = Comision::whereIn('idcomision', $request->input('comision_ids'))
                ->with(['personComision.user', 'facturas'])
                ->get();

            $processedCount = 0;

            foreach ($comisiones as $comision) {
                $importeOriginal = (float) $comision->importe;

                $currentUpdateData = $updateDataGeneral;

                if ($hasValue('idstatuscomision')) {
                    $newStatus = (int) $request->input('idstatuscomision');

                    if (Auth::user()->idtype != 1 && !in_array($newStatus, [11, 14], true)) {
                        $tieneFacturasPendientes = $comision->facturas
                            ->contains('cancelacion', 0);

                        $rev1NoAutorizado = $comision->rev1_estatus != 1;

                        if ($tieneFacturasPendientes || $rev1NoAutorizado) {
                            $motivo = $tieneFacturasPendientes
                                ? 'tiene facturas pendientes de revisión'
                                : 'aún no cuenta con mínimo una revisión por parte de la DGPPR';

                            throw new \Exception(
                                "No se puede actualizar el estatus de la comisión " .
                                    "{$comision->comision} porque {$motivo}."
                            );
                        }
                    }

                    $currentUpdateData['idstatuscomision'] = $newStatus;

                    if (in_array($newStatus, $idStatusComprobacion, true)) {
                        $currentUpdateData['datecomprobation'] = DB::raw(
                            "TO_DATE('" . date('d-m-Y H:i:s') .
                                "', 'DD-MM-YYYY HH24:MI:SS')"
                        );
                    }

                    if (in_array($newStatus, [13, 22, 241, 242], true)) {
                        $nowOracle = date('Y-m-d H:i:s');

                        if (is_null($comision->rev1_estatus)) {
                            $currentUpdateData['rev1_estatus'] = 1;
                            $currentUpdateData['rev1_fecha'] = $nowOracle;
                        } elseif (
                            !is_null($comision->rev1_estatus) &&
                            is_null($comision->rev2_estatus)
                        ) {
                            $currentUpdateData['rev2_estatus'] = 1;
                            $currentUpdateData['rev2_fecha'] = $nowOracle;
                        }
                    }
                }

                $valorUnitarioInformado =
                    $hasValue('importComisionado') ||
                    $hasValue('importComision');

                if ($valorUnitarioInformado && !$isAmpliacion) {
                    $nuevoValorUnitario = $hasValue('importComisionado')
                        ? (float) $request->input('importComisionado')
                        : (float) $request->input('importComision');

                    $diasCompletos = (float) ($comision->dayscompletes ?? 0);
                    $mediosDias = (float) ($comision->hours ?? 0);

                    $currentUpdateData['importe'] =
                        ($diasCompletos * $nuevoValorUnitario) +
                        ($mediosDias * ($nuevoValorUnitario / 2));
                }

                if ($isAmpliacion) {
                    $fechaFinalActual = Carbon::parse($comision->finaldate);
                    $fechaNuevaFinal = Carbon::parse(
                        $request->input('dateReasignacionFinal')
                    );

                    if ($fechaNuevaFinal->lessThan($fechaFinalActual)) {
                        throw new \Exception(
                            "La fecha final para la comisión {$comision->comision} " .
                                "debe ser posterior a {$comision->finaldate}."
                        );
                    }

                    $diasAdicionales = $fechaNuevaFinal->diffInDays($fechaFinalActual);

                    if ($diasAdicionales === 0) {
                        throw new \Exception(
                            "La ampliación debe ser de al menos 1 día " .
                                "para la comisión {$comision->comision}."
                        );
                    }

                    $idUsuario = $comision->personComision->id_user;
                    $usuarioReal = \App\Models\User::find($idUsuario);

                    if (!$usuarioReal) {
                        throw new \Exception(
                            "No se encontró el usuario con ID {$idUsuario} " .
                                "para la comisión {$comision->comision}."
                        );
                    }

                    $nivel = $usuarioReal->cargo;
                    $importesReferencia = $this->calcularImportes($nivel);

                    \Log::info('Diagnóstico de tabulador para ampliación', [
                        'clase' => static::class,
                        'nivel' => $nivel,
                        'nivel_hex' => bin2hex((string) $nivel),
                        'resultado' => $importesReferencia,
                    ]);

                    if (!$importesReferencia) {
                        throw new \Exception(
                            "No se encontraron tabuladores para el nivel {$nivel}."
                        );
                    }

                    $importeDiaTabulador = $importesReferencia->completo;
                    $montoExtraAmpliacion = $diasAdicionales * $importeDiaTabulador;

                    Reasignacion::create([
                        'datereasignacioninicial' => $comision->finaldate,
                        'datereasignacionfinal' => $request->input('dateReasignacionFinal'),
                        'motivoampliacion' => $request->input('motivoAmpliacion'),
                        'newimport' => $montoExtraAmpliacion,
                        'idcomision' => $comision->idcomision,
                    ]);

                    $currentUpdateData['finaldate'] =
                        $request->input('dateReasignacionFinal');

                    $currentUpdateData['dayscompletes'] =
                        (float) $comision->dayscompletes + $diasAdicionales;

                    $currentUpdateData['importe'] =
                        $importeOriginal + $montoExtraAmpliacion;
                }

                if ($isReduccionAnticipada) {
                    $fechaFinalActual = Carbon::parse($comision->finaldate);
                    $fechaNuevaFinal = Carbon::parse(
                        $request->input('dateReasignacionFinal')
                    );

                    if ($fechaNuevaFinal->greaterThanOrEqualTo($fechaFinalActual)) {
                        throw new \Exception(
                            "La fecha final anticipada para la comisión " .
                                "{$comision->idcomision} debe ser anterior a la fecha original " .
                                "({$comision->finaldate})."
                        );
                    }

                    $diasRestados = $fechaFinalActual->diffInDays($fechaNuevaFinal);
                    $nivel = $comision->personComision->nivel;
                    $importesReferencia = $this->calcularImportes($nivel);

                    if (!$importesReferencia) {
                        throw new \Exception(
                            "No se encontraron tabuladores para el nivel {$nivel}."
                        );
                    }

                    $importeDiaTabulador = $importesReferencia->completo;
                    $montoMenosReduccion = $diasRestados * $importeDiaTabulador;

                    Reasignacion::create([
                        'datereasignacioninicial' => $comision->finaldate,
                        'datereasignacionfinal' => $request->input('dateReasignacionFinal'),
                        'motivoampliacion' =>
                        '[TERMINACIÓN ANTICIPADA] ' .
                            $request->input('motivoAmpliacion'),
                        'newimport' => -$montoMenosReduccion,
                        'idcomision' => $comision->idcomision,
                    ]);

                    $currentUpdateData['finaldate'] =
                        $request->input('dateReasignacionFinal');

                    $currentUpdateData['dayscompletes'] = max(
                        0,
                        (float) $comision->dayscompletes - $diasRestados
                    );

                    $importeBaseReduccion = (float) (
                        $currentUpdateData['importe'] ?? $comision->importe
                    );

                    $currentUpdateData['importe'] = max(
                        0,
                        $importeBaseReduccion - $montoMenosReduccion
                    );
                }
                if (!empty($currentUpdateData)) {
                    $comision->update($currentUpdateData);
                    $processedCount++;

                    if ($isPaymentStatus) {
                        $commissionsForMail[] = $comision;
                    }

                    if ($isReintegroStatus && $comision->autorizacion == 1) {
                        $idTipoReintegro =
                            (string) $request->input('idstatuscomision') === '3'
                            ? 1
                            : 2;

                        if ($idTipoReintegro === 1) {
                            $montoReintegro = (float) $comision->importe;
                        } else {
                            $montoComprobado = $comision->facturas
                                ->where('cancelacion', 2)
                                ->sum('importe');

                            $montoReintegro = max(
                                0,
                                (float) $comision->importe - $montoComprobado
                            );
                        }

                        if ($montoReintegro > 0) {
                            Reintegro::create([
                                'idComision' => $comision->idcomision,
                                'idTipoReintegro' => $idTipoReintegro,
                                'monto' => $montoReintegro,
                                'date' => Carbon::now(),
                                'comprobante' => '',
                                'idDeposito' => '',
                            ]);

                            $commissionsForReintegroMail[] = $comision;
                        }
                    }
                }
            }

            if ($processedCount === 0) {
                DB::rollBack();

                return response()->json([
                    'message' => 'No se realizaron cambios.',
                ], 400);
            }

            DB::commit();

            $listaViaticos = $comisiones->map(function ($comision) {
                $acronym = $comision->modalidad->acronym ?? 'S/A';

                return "{$acronym}_{$comision->comision}_{$comision->yearcomision}";
            })->implode(', ');

            DB::table('registros')->insert([
                'movimiento' =>
                "HA REALIZADO UNA EDICIÓN MASIVA " .
                    "(ESTADO {$request->input('idstatuscomision')}) " .
                    "A LAS SIGUIENTES COMISIONES: {$listaViaticos}",
                'id_user' => Auth::user()->id_user,
                'fecha_movimiento' => now(),
                'created_at' => date('Y-m-d H:i:s'),
                'idcomision' => json_encode($request->input('comision_ids')),
                'idstatus' => $request->input('idstatuscomision'),
            ]);

            return response()->json([
                'message' => "Se procesaron con éxito **{$processedCount}** comisiones.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function uploadComprobante(Request $request)
    {

        $request->validate(
            [
                'comprobante' => 'required|file|mimes:pdf,jpg,png|max:2048',
                'idtipodeposito' => 'required|integer',
            ],
            [
                'comprobante.mimes' => 'El comprobante debe tener el formato: .jpg, .png, pdf.'
            ]
        );

        $idComision = $request->input('idcomision');

        $reintegro = DB::connection('oracle_primary')
            ->table('reintegros')
            ->select('monto')
            ->where('idcomision', $idComision)
            ->first();



        if ($request->hasFile('comprobante')) {
            $file = $request->file('comprobante');
            $fileName = time() . '_' . $file->getClientOriginalName();

            $path = $file->storeAs('tickets', $fileName);
            // return $path;

            DB::connection('oracle_primary')
                ->table('reintegros')
                ->where('idcomision', $idComision)
                ->update([
                    'date' => now(),
                    'comprobante' => $fileName,
                    'iddeposito' => $request->get('idtipodeposito'),
                ]);
        }

        return redirect()->back()->with('success', 'El comprobante ha sido subido con éxito.');
    }
    public function downloadComprobante($comprobante)
    {
        return response()->file(storage_path('app/' . 'facturas/0A2CB8E6-756C-493A-8BAE-2B110D66EE23.pdf'));
    }
    public function layoutSicop()
    {
        return response()->file(storage_path('app/' . 'facturas/layout.sicop.xlsx'));
    }
    public function layoutBancario()
    {
        return response()->file(storage_path('app/' . 'facturas/layout.bancario.xlsx'));
    }

    public function detallesComision($idComisionHasheado)
    {
        try {
            $idComision = Crypt::decryptString($idComisionHasheado);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'El identificador de la comisión es inválido o ha sido alterado.');
        }

        $data = $this->getComisionData($idComision);
        $query = $data['query'];
        $user = $data['contacto'];

        if (!$query) {
            return redirect()->back()->with('error', 'Comisión no encontrada');
        }

        $reintegro = DB::connection('oracle_primary')
            ->table('reintegros')
            ->where('idcomision', $idComision)
            ->where('idtiporeintegro', '3')
            ->first();

        $autorizadores = DB::table('users')->where('idtype', '=', 2)->where('active', '=', 1)->orderBy('nomusuario', 'asc')->get();

        $importes_ley = $data['importes_ley'];
        $validador = $data['validador'];
        $contact = $data['contacto'];
        $reasignaciones = $data['reasignaciones'];
        $comprobaciones = $data['comprobaciones'];
        $idModalidad = $query->idmodalidad;

        $statusComision = StatusComision::where('idModalidad', '=', $idModalidad)
            ->where('idtype', Auth::user()->idtype)
            ->get();

        $tipoDepositos = DB::connection('oracle_primary')->table('tipodeposito')->get();

        $gastos = GastosSinComprobante::where('idcomision', $idComision)
            ->orderBy('datepay', 'asc')
            ->get();

        $arrayid = (array) $idComision;
        DB::table('registros')->insert([
            'movimiento' => "HA CONSULTADO LOS DETALLES DE LA COMISIÓN: ",
            'id_user' => Auth::user()->id_user,
            'fecha_movimiento' => now(),
            'created_at' => date('Y-m-d-H:i:s'),
            'idcomision' => json_encode($arrayid)
        ]);

        return view('comision.comisionFinalizada', compact(
            'query',
            'validador',
            'contact',
            'gastos',
            'autorizadores',
            'reasignaciones',
            'comprobaciones',
            'tipoDepositos',
            'idComision',
            'statusComision',
            'importes_ley',
            'reintegro'
        ));
    }
    public function gastosSinComprobar($idComisionHasheado)
    {
        try {
            $idComision = Crypt::decryptString($idComisionHasheado);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Identificador de comisión no válido.');
        }

        $data = $this->getComisionData($idComision);

        $query = $data['query'];

        if (!$query) {
            return redirect()->back()->with('error', 'Comisión no encontrada.');
        }

        $validador = $data['validador'];
        $contact = $data['contacto'];
        $reasignaciones = $data['reasignaciones'];
        $comprobaciones = $data['comprobaciones'];

        $tipoDepositos = DB::connection('oracle_primary')->table('tipodeposito')->get();

        $gastos = GastosSinComprobante::where('idcomision', $idComision)
            // ->where('activo', '=', 1)
            ->orderBy('datepay', 'asc')
            ->get();

        return view('comision.gastosSinComprobar', compact(
            'query',
            'validador',
            'contact',
            'reasignaciones',
            'comprobaciones',
            'tipoDepositos',
            'idComision',
            'gastos'
        ));
    }
    public function generarGastosSinComprobantePDF($idComisionHasheado)
    {
        try {
            $idComision = Crypt::decryptString($idComisionHasheado);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'El identificador de la comisión es inválido.');
        }
        $data = $this->getComisionData($idComision);

        if (!$data['query']) {
            return redirect()->back()->with('error', 'Comisión no encontrada para generar el PDF de acuse.');
        }

        $comision = $data['query'];

        $viatico = $comision->acronym . "_" . $comision->comision . "_" . $comision->yearcomision;

        $pdf = PDF::loadView('comprobaciones.gastosPDF', $data);
        $arrayid = (array) $idComision;
        DB::table('registros')->insert([
            'movimiento' => "HA CONSULTADO EL FORMATO DE GASTOS SIN COMPROBANTE DE LA COMISIÓN: ",
            'id_user' => Auth::user()->id_user,
            'fecha_movimiento' => now(),
            'created_at' => date('Y-m-d-H:i:s'),
            'idcomision' => json_encode($arrayid)
        ]);

        return $pdf->stream('comprobacion_' . $idComision . '.pdf');
    }

    public function downloadTicket($id)
    {
        $gasto = GastosSinComprobante::findOrFail($id);
        return Storage::disk('public')->download($gasto->proof_file);
    }
    public function destroy(Request $request)
    {
        try {
            $gasto = GastosSinComprobante::findOrFail($request->get('idsincomprobante'));

            $gasto->delete();
            DB::connection('oracle_primary')->table('sincomprobantes')->where('idsincomprobante', $request->get('idsincomprobante'))
                ->update([
                    'activo' => 0,
                    'observaciones' => $request->get('observaciones')
                ]);
            return redirect()->back()->with('success', 'Registro enviado a la papelera.');
        } catch (\Exception $e) {
            \Log::error("Error en GUARDAR: " . $e->getMessage());

            return redirect()->back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function active($id)
    {
        try {
            $idSinComprobante = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'El identificador del comprobante es inválido.');
        }
        try {
            $gasto = GastosSinComprobante::findOrFail($idSinComprobante);

            // $gasto->delete();
            DB::connection('oracle_primary')->table('sincomprobantes')->where('idsincomprobante', $idSinComprobante)
                ->update([
                    'activo' => 1,
                    'observaciones' => null
                ]);
            return redirect()->back()->with('success', 'Registro activado exitosamente.');
        } catch (\Exception $e) {
            \Log::error("Error en GUARDAR: " . $e->getMessage());

            return redirect()->back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function obtenerMovimientos($idComision)
    {
        $movimientos = Registros::with(['usuario', 'estatus'])
            ->where('idcomision', 'LIKE', '%["' . $idComision . '"]%')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('partials.tabla_movimientos', compact('movimientos'));
    }
}
