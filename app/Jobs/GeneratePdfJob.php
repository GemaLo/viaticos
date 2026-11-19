<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Admin\MoviemientoController;
use Illuminate\Support\Facades\Log;

class GeneratePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $folio, $xmlSat, $laudos, $siri, $informativos, $nombre, $value, $index, $idUser;

    /**
     * Create a new job instance.
     */
    public function __construct($folio, $xmlSat, $laudos, $siri, $informativos, $nombre, $value, $index, $idUser)
    // 
    {
        // Log::info("Job creado: Folio {$folio}, Index: {$index}");
// dd("si llegue a constructor");
        $this->folio = $folio;
        $this->xmlSat = $xmlSat;
        $this->laudos = $laudos;
        $this->siri = $siri;
        $this->informativos = $informativos;
        $this->nombre = $nombre;
        $this->value = $value; 
        $this->index = $index;
        $this->idUser = $idUser;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // dd("llegue a al nuevo");
        Log::info("Ejecutando Job: Folio {$this->folio}, Index: {$this->index}");

        // Instanciar el controlador para llamar la función CreacionPDF
        $controlador = new MoviemientoController();

        // Llamar a la función de generación de PDF
        $controlador->CreacionPDF(
            $this->folio,
            $this->xmlSat,
            $this->laudos,
            $this->siri,
            $this->informativos,
            $this->nombre,
            $this->value,  // ✅ `$value` se usa directamente
            $this->index,
            $this->idUser
        );

        Log::info("Job Finalizado: Folio {$this->folio}, Index: {$this->index}");
    }
}
