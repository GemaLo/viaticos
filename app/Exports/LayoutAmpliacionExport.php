<?php

namespace App\Exports;

use App\Models\Reasignacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class LayoutAmpliacionExport implements FromCollection, WithHeadings, WithMapping
{
    protected $idReasignacion;

    public function __construct($idReasignacion)
    {
        $this->idReasignacion = $idReasignacion;
    }


    public function collection()
    {
        $query = Reasignacion::with('comision.personcomision.user');

        $query->where('idreasignacion', $this->idReasignacion);


        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Cuenta de Depósito',
            'Cuenta de Retiro',
            'Divisa',
            'Importe',
            'MOTIVO DE PAGO',
            'Referencia Numérica',
            'Titular de la cuenta de Depósito',
            'Banco de la Cuenta',
            'Disponibilidad',
            'COMPROBANTE FISCAL',
            'RFC BENEFICIARIO',
            'IVA DEL MONTO PAGADO',
        ];
    }

    public function map($reasignacion): array
    {
        $comision = $reasignacion->comision;

        if (!$comision || !$comision->personcomision || !$comision->personcomision->user) {
            return [];
        }

        $personComision = $comision->personcomision;

        $appUsuario = optional($personComision->user)->appusuario;
        $apmUsuario = optional($personComision->user)->apmusuario;
        $nomUsuario = optional($personComision->user)->nomusuario;

        $nombreCompleto = trim("{$nomUsuario} {$appUsuario} {$apmUsuario}");

        $prefijo = ($comision->idtypecomision == '1') ? 'VA' : 'VD';

        $motivoPago = "AMPLIACION_{$prefijo}_{$comision->comision}_{$comision->yearcomision}";

        return [
            $personComision->cuenta,
            $personComision->clabe,
            'MXN',

            $reasignacion->newimport,

            $motivoPago,

            $reasignacion->idreasignacion,

            $nombreCompleto,
            $personComision->banco,
            '',
            '',
            $personComision->rfc,
            '',
        ];
    }
}
