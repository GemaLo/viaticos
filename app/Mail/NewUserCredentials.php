<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUserCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public $mail;
    public $randomPassword;
    public $documentPath;

    public function __construct($mail, $randomPassword, $documentPath)
    {
        $this->mail = $mail;
        $this->randomPassword = $randomPassword;
        $this->documentPath = $documentPath;
    }

    public function build()
    {
        $email = $this->view("mail.credentials")
            ->subject("Sistema de Viáticos");

        if ($this->documentPath && file_exists($this->documentPath)) {
            $email->attach($this->documentPath, [
                'as' => 'Carta_Compromiso_Personalizada.docx',
                'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ]);
        }

        return $email;
    }
}
