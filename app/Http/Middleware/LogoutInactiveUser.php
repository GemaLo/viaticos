<?php
namespace App\Http\Middleware;

use App\Http\Controllers\Auth\LoginController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Sesion;
use Illuminate\Support\Facades\Session;

class LogoutInactiveUser
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = session('last_activity');

            // Si ha pasado más de 20 minutos (1200 segundos)
            if ($lastActivity && Carbon::parse($lastActivity)->diffInMinutes(now()) > 20) {

                $cierreSessionId = Session::get('sesion_id');

                if ($cierreSessionId) {
                    // Buscar el modelo correspondiente al ID guardado en la sesión
                    $cierreSession = Sesion::find($cierreSessionId);
                    if ($cierreSession) {
                        $cierreSession->hora_salida = Carbon::now('America/Mexico_City')->format('Y-m-d');
                        $cierreSession->save(); 
                    }
                }
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                return redirect('/login')->with('message', 'Sesión cerrada por inactividad.');
            }

            // Actualizar la última actividad
            session(['last_activity' => now()]);
        }

        return $next($request);
    }
}
