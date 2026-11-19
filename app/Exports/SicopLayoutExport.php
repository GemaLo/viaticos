<?php

namespace App\Exports;

use App\Models\Factura;
use App\Models\GastosSinComprobante;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class SicopLayoutExport implements FromCollection, WithMapping, WithHeadings
{
    protected $comisionIds;

    public function __construct(array $comisionIds)
    {
        $this->comisionIds = $comisionIds;
    }

    public function collection()
    {
        if (empty($this->comisionIds)) {
            return new Collection([]);
        }

        $facturas = Factura::with(['comision.modalidad'])
            ->whereIn('idcomision', $this->comisionIds)
            ->where('cancelacion', 2)
            ->get();

        $gastosAgrupados = GastosSinComprobante::with(['comision.modalidad'])
            ->whereIn('idcomision', $this->comisionIds)
            ->where('activo', 1)
            ->get()
            ->groupBy('idcomision')
            ->map(function ($grupo) {
                $base = $grupo->first();
                $totalMonto = $grupo->sum(function ($item) {
                    return (float) $item->monto;
                });
                $base->monto = $totalMonto;

                return $base;
            });

        $coleccionUnida = $facturas->concat($gastosAgrupados);

        return $coleccionUnida->sortBy(function ($item) {
            return $item->comision->comision ?? 0;
        })->values();
    }

    public function map($item): array
    {
        if ($item instanceof Factura) {
            $tasa = (int)round((float)($item->tasa ?? 0) * 100);
            $clasificacion = $this->getClasificacion($tasa);

            return [
                '36',
                $item->cfdi,
                $item->datefactura ? date('d/m/Y', strtotime($item->datefactura)) : '',
                $item->created_at ? date('d/m/Y', strtotime($item->created_at)) : '',
                $this->getBeneficiario($item->rfcemisor),
                '04',
                '85',
                $clasificacion,
                $tasa,
                number_format($item->subtotal ?? 0, 2, '.', ''),
                number_format($item->iva ?? 0, 2, '.', ''),
                '0.00',
                number_format($item->isr ?? 0, 2, '.', ''),
                '0.00',
                '0.00',
                '0.00',
                '0.00',
                $this->getOtrasContribuciones($item),
                '',
                $this->getDescripcion($item)
            ];
        }

        return [
            '36',
            'S/F',
            $item->datepay ? date('d/m/Y', strtotime($item->datepay)) : '',
            $item->created_at ? date('d/m/Y', strtotime($item->created_at)) : '',
            '749784',
            '04',
            '85',
            '05',
            0,
            number_format($item->monto ?? 0, 2, '.', ''),
            '0.00',
            '0.00',
            '0.00',
            '0.00',
            '0.00',
            '0.00',
            '0.00',
            '0.00',
            '',
            $this->getDescripcion($item)
        ];
    }

    private function getClasificacion($tasa)
    {
        $clasif = match ($tasa) {
            16 => '01',
            10 => '02',
            0 => '05',
            11 => '08',
            4 => '09',
            8 => '10',
            default => '03'
        };
        return ($tasa == 16) ? '07' : $clasif;
    }

    private function getBeneficiario($rfc)
    {
        return \App\Models\Beneficiario::where('rfc', $rfc)->first()->codigo_beneficiario ?? '';
    }

    private function getOtrasContribuciones($item)
    {
        $locales = is_array($item->impuestos_locales) ? $item->impuestos_locales : json_decode($item->impuestos_locales, true) ?? [];
        return number_format(($item->ieps ?? 0) + array_sum(array_column($locales, 'importe')), 2, '.', '');
    }

    private function getDescripcion($item)
    {
        $c = $item->comision;
        return "COMP_" . ($c->modalidad->acronym ?? 'SIN_MOD') . "_UR_" . ($c->oficio ?? 'N/A') . "_FOLIO_" . ($c->comision ?? 'N/A');
    }

    public function headings(): array
    {
        return [['H', '36', '143', '', 10]];
    }
}
