<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comision;
use App\Models\Factura;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FacturasEstadoImport;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class ValidacionController extends Controller
{
    public function validacionForm($idComision)
    {
        $query =  Comision::with('personComision.user')
            ->where('idComision', $idComision)
            ->firstOrFail();

        $facturasAcumuladas = Factura::where('idcomision', $idComision)->where('cancelacion', NULL)->sum('importe');
        return view('validaciones.validacionForm', compact('query', 'idComision', 'facturasAcumuladas'));
    }

public function importStatus(Request $request)
{
    $request->validate([
        'excel_file' => 'required|mimes:xlsx,xls|max:10240',
    ]);

    DB::beginTransaction();

    try {
        $import = new FacturasEstadoImport;

        Excel::import($import, $request->file('excel_file'));

        DB::commit();

        return redirect()->back()->with([
            'success' => 'Estados de facturas actualizados exitosamente.',
            'facturas_actualizadas' => $import->actualizadas
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Hubo un error al procesar el archivo: ' . $e->getMessage());
    }
}

public function downloadXml($cfdi)
{
    try {
        $cfdiDecrypted = Crypt::decryptString($cfdi);
    } catch (DecryptException $e) {
        return redirect()->back()->with('error', 'El identificador de la factura no es válido.');
    }

    $path = storage_path('app/facturas/' . $cfdiDecrypted . '.xml');

    if (!file_exists($path)) {
        return redirect()->back()->with('warning', 'No es posible descargar el archivo. Compruebe el nombre del archivo o verifique si se guardó correctamente.');
    }

    $headers = [
        'Content-Type' => 'application/xml',
        'Content-Disposition' => 'attachment; filename="' . $cfdiDecrypted . '.xml"',
    ];

    return response()->file($path, $headers);
}
}
