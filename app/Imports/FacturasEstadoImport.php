<?php

namespace App\Imports;

use App\Models\Factura;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FacturasEstadoImport implements ToModel, WithHeadingRow
{
    public array $actualizadas = [];

    public function model(array $row)
    {
        $uuidExcelColumnName = 'uuid';
        $estadoExcelColumnName = 'estado';

        $uuidFromExcel = $row[$uuidExcelColumnName] ?? null;
        $estado = $row[$estadoExcelColumnName] ?? null;

        if ($uuidFromExcel) {
            $factura = Factura::with(['comision.modalidad'])->where('cfdi', $uuidFromExcel)->first();

            if ($factura && $estado !== null) {
                $factura->status = $estado;
                $factura->save();

                $this->actualizadas[] = [
                    'comision'   => $factura->comision->comision ?? 'N/A',
                    'cfdi'       => $factura->cfdi,
                    'modalidad'  => $factura->comision->modalidad->modalidad ?? 'N/A',
                    'cancelacion'=> $factura->cancelacion ?? 'N/A',
                    'status'=> $factura->status ?? 'N/A',
                    'clc'=> $factura->comision->clc ?? 'No tiene CLC asignada',
                    'statuscomision'=> $factura->comision->statusComision->statuscomision ?? 'N/A',
                    'ur'=> $factura->comision->oficio
                ];
            }
        }

        return null;
    }
}
