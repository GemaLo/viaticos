<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FacturasEstadoImport;
use App\Models\Comision;
use Exception;
use Illuminate\Support\Facades\DB;


class FacturasController extends Controller
{
    public function importStatus(Request $request)
    {
        try {
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls'
            ]);
            Excel::import(new FacturasEstadoImport, $request->file('excel_file'));
            return redirect()->back()->with('success', 'La actualización de estados se ha completado con éxito.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error durante la importación: ' . $e->getMessage());
        }
    }
    
}
