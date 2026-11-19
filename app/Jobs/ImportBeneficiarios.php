<?php

namespace App\Jobs;

use App\Models\Beneficiario;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache; 
use Illuminate\Support\Carbon;
use Exception;

class ImportBeneficiarios implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $importKey; 

    public $tries = 1;
    public $timeout = 600;

    public function __construct($filePath, $importKey)
    {
        $this->filePath = $filePath;
        $this->importKey = $importKey; 
    }

    public function handle()
    {
        Cache::put($this->importKey, ['status' => 'processing', 'processed' => 0], 1800);

        Log::info("Iniciando lectura de registros en segundo plano para: {$this->filePath}");

        if (!file_exists($this->filePath)) {
            Cache::put($this->importKey, ['status' => 'failed', 'error' => 'El archivo no existe'], 1800);
            return;
        }

        // --- VACIAR LA TABLA ANTES DE PROCESAR ---
        try {
            Beneficiario::truncate();
        } catch (\Exception $e) {
            // Si truncate() falla por llaves foráneas, borra todos los registros con query()
            Beneficiario::query()->delete();
        }

        if (($handle = fopen($this->filePath, "r")) !== FALSE) {
            fgetcsv($handle, 1000, ","); // Omitir encabezado

            $chunkSize = 1000;
            $insertBuffer = [];
            $processedCount = 0;

            app('db')->connection()->disableQueryLog();

            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (empty($row[0]) || count($row) < 6) {
                    continue; 
                }

                $fechaAlta = null;
                if (!empty($row[2])) {
                    try {
                        $fechaAlta = Carbon::parse(str_replace('/', '-', trim($row[2])))->format('Y-m-d');
                    } catch (\Exception $e) {
                        $fechaAlta = null;
                    }
                }

                $insertBuffer[] = [
                    'rfc'                 => trim($row[0]),
                    'razon_social'        => trim($row[1]),
                    'fecha_alta'          => $fechaAlta,
                    'activo'              => trim($row[3]),
                    'id_ramo'             => is_numeric($row[4]) ? intval($row[4]) : null,
                    'codigo_beneficiario' => trim($row[5]),
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ];

                if (count($insertBuffer) >= $chunkSize) {
                    Beneficiario::insert($insertBuffer);
                    $processedCount += count($insertBuffer);
                    $insertBuffer = [];
                    
                    Cache::put($this->importKey, ['status' => 'processing', 'processed' => $processedCount], 1800);
                }
            }

            if (!empty($insertBuffer)) {
                Beneficiario::insert($insertBuffer);
                $processedCount += count($insertBuffer);
            }

            fclose($handle);

            if (file_exists($this->filePath)) {
                unlink($this->filePath);
            }

            Cache::put($this->importKey, ['status' => 'completed', 'processed' => $processedCount], 1800);
            Log::info("Importación finalizada. Total: {$processedCount}");
        } else {
            Cache::put($this->importKey, ['status' => 'failed', 'error' => 'No se pudo leer el archivo'], 1800);
        }
    }

    public function failed(Exception $exception)
    {
        Cache::put($this->importKey, ['status' => 'failed', 'error' => $exception->getMessage()], 1800);
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }
}