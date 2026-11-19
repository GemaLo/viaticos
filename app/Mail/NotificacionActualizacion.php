<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionActualizacion extends Mailable
{
    use Queueable, SerializesModels;

    public $mail;
    public $randomPassword;


    public function __construct($mail, $randomPassword){
        $this->mail = $mail;
        $this->randomPassword = $randomPassword;

    }


    public function build()
    {
        $fecha_reporte = date("d-m-Y");
        return $this->view("mail.notificacionActualizacion")
            ->subject("Sistema de Viáticos");
    }

}
