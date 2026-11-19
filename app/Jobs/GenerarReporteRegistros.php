<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
use App\Exports\RegistrosExport;
use Throwable;

class GenerarReporteRegistros implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 3600;

    public function __construct(
        protected ?string $userId,
        protected string $fechaInicio,
        protected string $fechaFin,
        protected string $jobId,
        protected string $fileName, 
        protected ?string $textoMovimiento = null
    ) {}

    public function handle(): void
    {
        DB::reconnect();
        try {
            Storage::disk('local')->makeDirectory('exports');
            Log::info('Iniciando export XLSX', [
                'jobId'    => $this->jobId,
                'fileName' => $this->fileName,
            ]);

            Excel::store(
                new RegistrosExport(
                    $this->userId,
                    $this->fechaInicio,
                    $this->fechaFin,
                    $this->jobId,
                    $this->textoMovimiento
                ),
                $this->fileName, 
                'local',
                ExcelFormat::XLSX
            );

            $fullPath = Storage::disk('local')->path($this->fileName);

            Log::info('Export terminado', [
                'jobId'  => $this->jobId,
                'exists' => file_exists($fullPath),
                'size'   => file_exists($fullPath) ? filesize($fullPath) : 0,
            ]);

            DB::table('export_jobs')
                ->where('id', $this->jobId)
                ->update([
                    'status'     => 'done',
                    'file_path'  => $this->fileName,
                    'updated_at' => now(),
                ]);
        } catch (Throwable $e) {
            Log::error('ERROR EN JOB', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function failed(Throwable $e): void
    {
        DB::table('export_jobs')
            ->where('id', $this->jobId)
            ->update([
                'status'     => 'failed',
                'error'      => $e->getMessage(),
                'updated_at' => now(),
            ]);
    }
}
