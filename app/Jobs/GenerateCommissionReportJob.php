<?php

namespace App\Jobs;

use App\Exports\CommissionReportExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GenerateCommissionReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;

    public function __construct(
        protected string $reportId,
        protected string $fileName,
        protected array $filters,
        protected $idtype 
    ) {}

    public function handle(): void
    {
        $cacheKey = "report_{$this->reportId}";

        try {
            \Maatwebsite\Excel\Facades\Excel::store(
                new \App\Exports\CommissionReportExport($this->filters, $this->idtype),
                $this->fileName,
                'public'
            );

            \Illuminate\Support\Facades\Cache::put("report_{$this->reportId}", [
                'status' => 'completed',
'file_name' => $this->fileName,
'file_path' => 'storage/' . $this->fileName,
                'error_message' => null
            ], 3600);
        } catch (\Exception $e) {
            \Log::error("Error en Job GenerateCommissionReportJob: " . $e->getMessage());

            \Illuminate\Support\Facades\Cache::put("report_{$this->reportId}", [
                'status' => 'failed',
                'file_name' => $this->fileName,
                'file_path' => null,
                'error_message' => $e->getMessage()
            ], 3600);

            throw $e;
        }
    }
}
