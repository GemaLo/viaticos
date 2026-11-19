<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ResetPasswordState;

class ResetPasswordCommand extends Command
{
    protected $signature = 'users:reset-passwords';
    protected $description = 'Enviar a la cola el Job para actualizar los estados de contraseña';

    public function handle()
    {
        ResetPasswordState::dispatch(); // Enviar Job a la cola
        $this->info('Se ha enviado el Job para resetear contraseñas.');
    }
}
