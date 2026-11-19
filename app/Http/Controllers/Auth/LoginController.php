<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Mail\RecuperacionAccesosMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Sesion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $request->session()->regenerate();

                if ($user->active == 0) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'La cuenta no está activa. Para más información, contacta a tu enlace de área.',
                    ]);
                }
                try {
                    $info = $user->numempleado;
                    $datos = null;

                    if ($user->procedencia === 'SECRETARÍA') {
                        $datos = DB::connection('oracle_secondary')
                            ->table('EMPLEADOS_VIATICOS')
                            ->where('NUM_EMPLEADO', $info)
                            ->first();
                    } elseif ($user->procedencia === 'GUARDIA') {
                        $datos = DB::connection('gnnomina')
                            ->table('EMPLEADOS_VIATICOS_GN')
                            ->where('NUM_EMPLEADO', $info)
                            ->first();
                    }
                } catch (\Exception $e) {
                    $datos = null;
                }

                if ($datos) {
                    session(['SessionUr' => $datos->unidad]);
                }

                DB::table('registros')->insert(['movimiento' => 'HA INICIADO SESIÓN','id_user' => $user->id_user,'fecha_movimiento' => now()]);

                return redirect()->intended($this->redirectPath());
            }

            return back()->withErrors([
                'email' => 'Usuario o contraseña incorrectos, por favor vuelva a intentarlo.',
            ]);
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Servicio temporalmente no disponible. Contacta al área de soporte si el problema persiste.',
            ]);
        }
    }
    protected function redirectPath()
    {
        if (Auth::user()->idtype == "1") {
            return '/Dashboard-Admin';
        }
        return '/Dashboard';
    }

    public function logout(Request $request)
    {
        $this->eliminarArchivosTemporales();

        $cierreSessionId = Session::get('sesion_id');

        if ($cierreSessionId) {
            $cierreSession = Sesion::find($cierreSessionId);
            if ($cierreSession) {
                $cierreSession->hora_salida = Carbon::now('America/Mexico_City')->format('Y-m-d');
                $cierreSession->save();
            }
        }
        DB::table('registros')->insert(['movimiento' => 'HA CERRADO SU SESIÓN', 'id_user' => Auth::user()->id_user, 'fecha_movimiento' => date('Y-m-d H:m:s')]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    private function eliminarArchivosTemporales()
    {
        $idUser = Auth::user()->id_user;
        $tempPath = storage_path("app/temp/$idUser");

        if (File::exists($tempPath)) {
            File::deleteDirectory($tempPath);
        }
    }
}
