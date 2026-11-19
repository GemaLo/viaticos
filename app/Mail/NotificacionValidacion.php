<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Comision; 

class NotificacionValidacion extends Mailable
{
    use Queueable, SerializesModels;

    public $comision;

    public function __construct(Comision $comision) 
    {
        $this->comision = $comision;
    }

    public function envelope(): Envelope
    {
        $folio = $this->comision->folio ?? 'N/A';
        return new Envelope(
            subject: 'Notificación de Validación DGPPR - Folio: ' . $folio,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.notificacionValidacion',
            with: [
                'comision' => $this->comision,
                'comisionado' => $this->comision->personComision->user ?? null,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}