<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarcarExportListo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $jobId,
        protected string $filePath
    ) {}

    public function handle(): void
    {
        Log::info("[Export:{$this->jobId}] Marcando como done", [
            'file_path' => $this->filePath,
        ]);

        DB::table('export_jobs')
            ->where('id', $this->jobId)
            ->update([
                'status'     => 'done',
                'file_path'  => $this->filePath,
                'updated_at' => now(),
            ]);

        Log::info("[Export:{$this->jobId}] Listo ✓");
    }

    public function failed(\Throwable $e): void
    {
        Log::error("[Export:{$this->jobId}] MarcarExportListo falló", [
            'error' => $e->getMessage(),
        ]);

        DB::table('export_jobs')
            ->where('id', $this->jobId)
            ->update([
                'status'     => 'failed',
                'error'      => $e->getMessage(),
                'updated_at' => now(),
            ]);
    }
}
