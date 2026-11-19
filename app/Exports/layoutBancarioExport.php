<?php

namespace App\Exports;

use App\Models\Comision;
use Illuminate\Support\Facades\Auth; // 1. Importar la fachada Auth
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class layoutBancarioExport implements FromCollection, WithHeadings, WithMapping
{
    protected $comisionIds;
    protected $esDevengado = false;

    public function __construct($comisionIds = null)
    {
        $this->comisionIds = is_array($comisionIds) ? $comisionIds : (empty($comisionIds) ? [] : [$comisionIds]);

        if (!empty($this->comisionIds)) {
            $check = Comision::whereIn('idcomision', $this->comisionIds)->first();
            if ($check && $check->idmodalidad != '1') {
                $this->esDevengado = true;
            }
        }
    }

    public function collection()
    {
        $query = Comision::with(['personComision.user', 'reasignacion', 'facturas'])->orderBy('comision', 'ASC');

        if (!empty($this->comisionIds)) {
            $query->whereIn('idcomision', $this->comisionIds);
        } else {
            $query->whereIn('idstatuscomision', [5, 22, 11, 25, 26]);
        }
        return $query->get();
    }

    public function headings(): array
    {
        if ($this->esDevengado) {
            return [
                'num_empleado',
                'curp',
                'fecha_presentacion',
                'fecha_pago',
                'importe',
                'referencia_numerica',
                'referencia_leyenda'
            ];
        }

        return [
            'Tipo de Movimiento',
            'Cuenta de Depósito',
            'Cuenta de Retiro',
            'Divisa',
            'Importe',
            'Motivo de pago',
            'Referencia Numerica',
            'Titular de la cuenta de Deposito',
            'Banco de la Cuenta',
            'Disponibilidad',
            'Comprobante Fiscal',
            'RFC Beneficiario',
            'IVA del Monto Pagado',
        ];
    }

    public function map($comision): array
    {
        $personComision = $comision->personComision;
        $user = optional($personComision)->user;

        $unidadStr = $comision->oficio ?? 'S_U';
        $modalidadViatio = ($comision->idmodalidad == '1') ? 'VA' : 'VD';

        if ($comision->idmodalidad == '1') {
            $importeAPagar = $comision->reasignacion ? $comision->reasignacion->newimport : $comision->importe;
            $banco = optional($personComision)->banco;

            $cuentaODClabe = '';
            $nombreCompleto = '';
            $rfc = $personComision->rfc ?? '';

            if ($personComision && $banco) {
                if (strtoupper($banco) === 'BBVA BANCOMER') {
                    $cuentaODClabe = $personComision->cuenta;
                } else {
                    $cuentaODClabe = $personComision->clabe;
                    $nombreCompleto = trim(($user->nomusuario ?? '') . ' ' . ($user->appusuario ?? '') . ' ' . ($user->apmusuario ?? ''));
                }
            }

            $authUser = Auth::user();
            $nomInicial = $authUser->nomusuario ?? $authUser->nomUsuario ?? 'X';
            $appInicial = $authUser->appusuario ?? $authUser->appUsuario ?? 'X';
            $apmInicial = $authUser->apmusuario ?? $authUser->apmUsuario ?? 'X';

            $iniciales = strtoupper(substr($nomInicial, 0, 1) . substr($appInicial, 0, 1) . substr($apmInicial, 0, 1));
            $motivoPago = "{$unidadStr}_{$iniciales}_{$comision->yearcomision}_{$modalidadViatio}_{$comision->comision}";
            
            if ($comision->clc) {
                $motivoPago .= "_CLC_{$comision->clc}";
            }

            return [
                'Pago',
                $cuentaODClabe,
                '0121917749',
                'MXN',
                $importeAPagar,
                $motivoPago,
                $comision->idcomision,
                $nombreCompleto,
                $banco,
                '',
                '',
                $rfc,
                '',
            ];
        } else {
            $importeFacturas = $comision->facturas
                ->where('cancelacion', '2')
                ->sum(fn($f) => (float) $f->importe);
            $importeSinComprobantes = $comision->sinComprobantes->where('activo', '1')->sum(fn($i) => (float) $i->monto);
            $totalAPagar = $importeFacturas + $importeSinComprobantes;
            $refLeyenda = "R36_SSPC_UR_{$unidadStr}_{$modalidadViatio}{$comision->comision}_PAGO_VIATICOS";

            return [
                $user->numempleado ?? '',
                $personComision->curp ?? '',
                '',
                '',
                $totalAPagar,
                $comision->clc,
                $refLeyenda,
            ];
        }
    }
}