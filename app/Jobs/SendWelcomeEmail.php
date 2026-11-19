<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecuperacionAccesosMailable;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $password;
    // public $connection = 'oracle_primary';

    /**
     * Crea una nueva instancia del Job.
     */
    public function __construct($email, $password)
    {

        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Ejecuta el Job.
     */
    public function handle()
    {
        // Mail::to($this->email)->send(new RecuperacionAccesosMailable($this->password));

        try {
            Mail::to($this->email)->send(new RecuperacionAccesosMailable($this->password));

            // Registro de éxito
            Log::channel('correos')->info("✅ Correo enviado exitosamente a: {$this->email}");
        } catch (\Exception $e) {
            // Registro del error
            Log::channel('correos')->error("❌ Error al enviar a: {$this->email} | Motivo: " . $e->getMessage());
        }

    }
}
