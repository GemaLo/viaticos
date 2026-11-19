<?php

namespace App\Jobs;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResetPasswordState implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // timpo limite para restablecer contrasena
        $tiempoLimite = Carbon::now('America/Mexico_City')->subDay(60);

        // Buscar usuarios que deben cambiar su contraseña
        $usuarios = User::where('resetPass', 0)
            ->where('updated_at', '<', $tiempoLimite)
            ->get();

        foreach ($usuarios as $usuario) {
            $usuario->update(['resetPass' => 1]);

        }
    }
}
