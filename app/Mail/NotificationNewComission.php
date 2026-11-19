<?php

namespace App\Mail;

use App\Models\Comision;
use App\Models\User; 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationNewComission extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Comision $comision;
    public User $infoUser; 

    public function __construct(Comision $comision, User $infoUser)
    {
        $this->comision = $comision;
        $this->infoUser = $infoUser;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva Comisión Registrada: Folio ' . $this->comision->folio,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.newComision',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}