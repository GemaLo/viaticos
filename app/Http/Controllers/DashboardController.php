<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Comision;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
public function index()
    {
        $user = Auth::user();
        $nomusuario = $user->nomusuario;
        $idType = $user->idtype;

        $comisiones = collect();

        if ($idType == 2) {
            $unidadUsuario = $user->unidad;
            $comisiones = Comision::whereHas('personComision.user', function ($query) use ($unidadUsuario) {
                $query->where('unidad', $unidadUsuario);
            })->get();
        } else {
            $comisiones = Comision::all();
        }
        $status_en_solicitud = [1, 16];
        $status_en_proceso_pago = [9, 19];
        $status_en_comprobacion = [20];

        $count_en_solicitud = $comisiones->whereIn('idstatuscomision', $status_en_solicitud)->count();
        $count_en_proceso_pago = $comisiones->whereIn('idstatuscomision', $status_en_proceso_pago)->count();
        $count_en_comprobacion = $comisiones->whereIn('idstatuscomision', $status_en_comprobacion)->count();

        return view('dashboard.index', compact(
            'nomusuario',
            'comisiones',
            'idType',
            'count_en_solicitud',
            'count_en_proceso_pago',
            'count_en_comprobacion'
        ));
    }

    public function downloadConstancia()
    {
        $fileName = 'SAT-SSPC.pdf';
        $filePath = public_path('docs/' . $fileName);
        return response()->download($filePath, 'Constancia-SAT-SSPC.pdf');
    }

        public function lineamientos()
    {
        $fileName = 'LINEAMIENTOS VIATICOS VF.pdf';
        $filePath = public_path('docs/' . $fileName);
        return response()->download($filePath, 'LINEAMIENTOS VIATICOS VF.pdf');
    }
}
