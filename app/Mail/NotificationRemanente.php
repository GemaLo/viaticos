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
class NotificationRemanente extends Mailable
{
    use Queueable, SerializesModels;

    public $comisiones;
    public $actionType;

    public function __construct(array $comisiones, string $actionType)
    {
        $this->comisiones = $comisiones;
        $this->actionType = $actionType;
    }

    public function build()
    {
        $subject = 'Notificación: Termino de comisión';

        return $this->subject($subject)
                    ->view('mail.notificacionTermino');
    }
}