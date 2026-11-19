<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Asegúrate de importar el modelo User

class ChangePasswordController extends Controller
{
    public function resetPassword(){
        return view('cambiarPassword.resetpass');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }
        
        DB::connection('oracle_primary')
            ->update("
                UPDATE users 
                SET password = :password, 
                    must_change_password = :must_change_password, 
                    updated_at = :updated_at 
                WHERE id_user = :user_id", 
            [
                'password' => Hash::make($request->new_password),
                'must_change_password' => 0,
                'updated_at' => Carbon::now('America/Mexico_City'),
                'user_id' => $user->id_user, 
            ]);
        Auth::setUser(User::find($user->id_user)); 
        if ($user->idtype == "1") {
            return redirect('/Dashboard-Admin')->with('status', 'Su contraseña ha sido actualizada exitosamente.');
        } 
        return redirect('/Dashboard')->with('status', 'Su contraseña ha sido actualizada exitosamente.');
    }
}