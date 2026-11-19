<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Comision; 

class NotificationFacturas extends Mailable
{
    use Queueable, SerializesModels;

    public $comision; 

    public function __construct(Comision $comision)
    {
        $this->comision = $comision;
    }

    public function envelope(): Envelope
    {
        $personName = $this->comision->personComision->user->nomUsuario ?? 'Comisionado';

        return new Envelope(
            subject: 'Comisión Revisada: ' . $personName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.notificationFacturas',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}