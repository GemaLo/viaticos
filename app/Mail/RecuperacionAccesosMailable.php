<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class RecuperacionAccesosMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $nuevaContrasena;
    private $enlacePortal = 'https://ejemplo.com/nomina';
    public $usuario;
    /**
     * Create a new message instance.
     */
    public function __construct($nuevaContrasena,$nombreUsuario)
    {
        //
         $this->nuevaContrasena = $nuevaContrasena;
         $this->usuario = $nombreUsuario ;
     
    }



    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // asunto del correo
            from: new Address('usuarios.sirno@sspc.gob.mx','Recursos Humanos'),
            subject: 'Nuevo Portal para la Consulta de Recibos de Nómina',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        
        return new Content(
            view: 'mail.envioCorreoAccesos',
            with: [
                'usuario' => $this->usuario,
                'nuevaContrasena' => $this->nuevaContrasena,
                'enlacePortal' => $this->enlacePortal,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
