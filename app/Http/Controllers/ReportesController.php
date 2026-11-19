<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Reasignacion;
use App\Exports\SicopLayoutExport;
use App\Exports\layoutBancarioExport;
use App\Exports\LayoutAmpliacionExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Objetive;
use App\Jobs\GenerarReporteRegistros;
use Illuminate\Support\Facades\Storage;

class ReportesController extends Controller
{
    public function reportes(Request $request)
    {
        $comisionIds = $request->input('ids');
        $fileName = 'layout_bancario_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new layoutBancarioExport($comisionIds), $fileName);
    }

    public function reporteBancarioAmpliacion(Request $request)
    {
        $idReasignacion = $request->input('idreasignacion');
        if (empty($idReasignacion)) {
            return back()->with('error', 'El ID de la ampliación no fue proporcionado.');
        }
        $reasignacion = Reasignacion::find($idReasignacion);
        if (!$reasignacion) {
            return back()->with('error', 'La ampliación solicitada no existe.');
        }
        $fileName = 'Layout_Ampliacion_' . $reasignacion->idcomision . '_' . $idReasignacion . '.xlsx';
        return Excel::download(new LayoutAmpliacionExport($idReasignacion), $fileName);
    }

    public function downloadSicopLayout(Request $request)
    {
        $comisionIds = $request->input('ids');

        if (empty($comisionIds)) {
            return back()->with('error', 'Debe seleccionar al menos una comisión para generar el layout SICOP.');
        }

        $filename = 'layout_sicop_multiple_' . now()->format('Ymd_His') . '.csv';

        return Excel::download(new SicopLayoutExport($comisionIds), $filename, \Maatwebsite\Excel\Excel::CSV);
    }


    public function descargarRegistros(Request $request)
    {
        $request->validate([
            'fecha_inicio'     => 'nullable|date',
            'fecha_fin'        => 'nullable|date',
            'id_user'          => 'nullable',
            'texto_movimiento' => 'nullable|string|max:255',
        ]);

        $fechaInicio     = $request->input('fecha_inicio');
        $fechaFin        = $request->input('fecha_fin');
        $userId          = $request->input('id_user') ?? null;
        $textoMovimiento = $request->input('texto_movimiento') ?? null;

        if ($fechaInicio && $fechaFin) {
            $inicio = \Carbon\Carbon::parse($fechaInicio);
            $fin    = \Carbon\Carbon::parse($fechaFin);

            if ($fin->lt($inicio)) {
                return response()->json([
                    'message' => 'La fecha de fin no puede ser menor que la fecha de inicio.'
                ], 422);
            }

            if ($fin->diffInDays($inicio) > 31) {
                return response()->json([
                    'message' => 'El rango de fechas no puede ser mayor a un mes.'
                ], 422);
            }
        }

        $jobId    = (string) \Illuminate\Support\Str::uuid();
        $fileName = "exports/Reporte_{$jobId}.xlsx";

        DB::table('export_jobs')->insert([
            'id'         => $jobId,
            'status'     => 'pending',
            'file_path'  => $fileName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        GenerarReporteRegistros::dispatch(
            $userId,
            $fechaInicio,
            $fechaFin,
            $jobId,
            $fileName,
            $textoMovimiento
        );

        return response()->json(['job_id' => $jobId]);
    }

    public function statusExport(string $jobId)
    {
        $job = DB::table('export_jobs')->where('id', $jobId)->first();

        if (!$job) {
            return response()->json(['status' => 'not_found'], 404);
        }

        $response = ['status' => $job->status];

        if ($job->status === 'done') {
            $response['url'] = route('registros.export_download', ['job' => $jobId]);
        }

        if ($job->status === 'failed') {
            $response['error'] = $job->error;
        }

        return response()->json($response);
    }

    public function downloadExport(string $jobId)
    {
        $job = DB::table('export_jobs')->where('id', $jobId)->first();

        if (!$job || $job->status !== 'done') {
            abort(404, 'El reporte no existe o aún no ha finalizado.');
        }

        $fullPath = storage_path('app/' . $job->file_path);

        if (!file_exists($fullPath)) {
            \Illuminate\Support\Facades\Log::error("Archivo no encontrado en ruta física: {$fullPath}");
            abort(404, 'El archivo generado ya no existe en el servidor.');
        }

        $nombreDescarga = 'Reporte_Registros_' . now()->format('Ymd_His') . '.xlsx';

        return response()->download($fullPath, $nombreDescarga, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
    public function index()
    {
        $objetives = DB::table('objetives')
            ->orderBy(DB::raw('CAST("OBJETIVE" AS VARCHAR2(4000))'), 'ASC')
            ->get();
        $direcciones = DB::table('direcciones')->get();
        return view('admin.database', compact(['objetives', 'direcciones']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'objetive' => 'required|string',
            'ur' => 'required|array',
        ]);

        $objetivo = new Objetive();
        $objetivo->objetive = $request->objetive;
        $objetivo->ur = $request->get('ur');
        $objetivo->save();

        return redirect()->back()->with('success', 'Registro guardado correctamente.');
    }
}
