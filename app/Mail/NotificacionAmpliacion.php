<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Comision; 

class NotificacionAmpliacion extends Mailable
{
    use Queueable, SerializesModels;

    public $comision;
    public $importeNuevo;
    public $motivoAmpliacion;
    public $fechaFinalNueva;

    public function __construct(Comision $comision, float $importeNuevo, string $motivoAmpliacion, string $fechaFinalNueva)
    {
        $this->comision = $comision;
        $this->importeNuevo = $importeNuevo;
        $this->motivoAmpliacion = $motivoAmpliacion;
        $this->fechaFinalNueva = $fechaFinalNueva;
    }

    public function envelope(): Envelope
    {
        $folio = $this->comision->folio ?? 'N/A';
        return new Envelope(
            subject: 'Notificación de Aprobación de Ampliación - Folio: ' . $folio,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.notificacionAmpliacion',
            with: [
                'comision' => $this->comision,
                'comisionado' => $this->comision->personComision->user ?? null,
                'importeCalculadoAmpliacion' => $this->importeNuevo,
                'motivoAmpliacion' => $this->motivoAmpliacion,
                'fechaFinalNueva' => $this->fechaFinalNueva,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}