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

class NotificationStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $comisiones;
    public $actionType;
    public $justificacion;


public function __construct($comisiones, string $actionType, ?string $justificacion = null)
{
    $this->comisiones = $comisiones;
    $this->actionType = $actionType;
    $this->justificacion = $justificacion;
}
    
    public function build()
    {
        $firstComision = $this->comisiones[0] ?? null;

        $subject = ($this->actionType === 'aprobacion')
            ? 'Notificación: Aprobación de Comisiones'
            : 'Notificación: Cancelación de Comisiones';

        return $this->subject($subject)
            ->view('mail.notificationStatus');
    }
}
