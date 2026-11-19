<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMustChangePassword
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->must_change_password) {

            return redirect()->route('resetPassword')
                ->with('warning', 'Por favor, cambie su contraseña temporal antes de continuar.');
        }

        return $next($request);
    }
}
