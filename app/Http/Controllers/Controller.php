<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function dashboard()
    {
        $user = auth()->user();
       return view('dashboard.index',['user' => $user]);
    }

    public function pruebaruta(){
        return view('dashboard.pruebaformato');
    }
}
