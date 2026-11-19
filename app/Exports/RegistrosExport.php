<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\{
    FromQuery,
    WithMapping,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithEvents,
    WithChunkReading
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class RegistrosExport implements
    FromQuery,
    WithMapping,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithEvents,
    WithChunkReading
{
    use Exportable;

    private array $comisionesCache = [];

    public function __construct(
        protected ?string $id_user,
        protected string $fecha_inicio,
        protected string $fecha_fin,
        protected string $jobId,
        protected ?string $texto_movimiento = null 
    ) {
        $this->fecha_inicio .= ' 00:00:00';
        $this->fecha_fin    .= ' 23:59:59';
    }

    public function query()
    {
        return DB::table('registros as r')
            ->leftJoin('users as u', 'r.id_user', '=', 'u.id_user')
            ->leftJoin('statuscomision as s', 'r.idstatus', '=', 's.idstatuscomision')
            ->select(
                DB::raw("u.nomusuario || ' ' || u.appusuario || ' ' || u.apmusuario as nombre_completo"),
                'r.movimiento',
                'r.idcomision',
                'r.fecha_movimiento',
                's.statuscomision'
            )
            ->whereBetween('r.fecha_movimiento', [$this->fecha_inicio, $this->fecha_fin])
            ->when($this->id_user, fn($q) => $q->where('r.id_user', $this->id_user))
            ->when($this->texto_movimiento, function ($q) {
                $q->where(DB::raw('UPPER(r.movimiento)'), 'LIKE', '%' . strtoupper($this->texto_movimiento) . '%');
            })
            ->orderBy('r.fecha_movimiento', 'desc');
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function map($reg): array
    {
        return [
            $reg->nombre_completo,
            $reg->movimiento,
            $this->resolverComisiones($reg->idcomision),
            $reg->fecha_movimiento,
            $reg->statuscomision,
        ];
    }

    private function resolverComisiones(?string $idcomision): string
    {
        if (empty($idcomision)) {
            return 'SIN COMISIÓN ASIGNADA';
        }

        $decoded = json_decode($idcomision, true);
        $ids = is_array($decoded)
            ? $decoded
            : array_filter(array_map('trim', explode(',', str_replace(['[', ']', '"'], '', $idcomision))));

        $ids = array_values(array_filter($ids));

        if (empty($ids)) {
            return 'SIN COMISIÓN ASIGNADA';
        }

        $faltantes = array_diff($ids, array_keys($this->comisionesCache));

        if (!empty($faltantes)) {
            $nuevas = DB::table('comisiones')
                ->whereIn('idcomision', $faltantes)
                ->select('idcomision', 'folio', 'comision')
                ->get()
                ->keyBy('idcomision');

            foreach ($nuevas as $id => $comision) {
                $this->comisionesCache[$id] = $comision;
            }

            foreach ($faltantes as $id) {
                if (!isset($this->comisionesCache[$id])) {
                    $this->comisionesCache[$id] = null;
                }
            }
        }

        return collect($ids)->map(function ($id) {
            $id = trim($id);
            $c  = $this->comisionesCache[$id] ?? null;

            return "ID COMISIÓN: {$id}\n" .
                   "FOLIO: "    . strtoupper($c->folio    ?? 'N/A') . "\n" .
                   "COMISIÓN: " . strtoupper($c->comision ?? 'N/A');
        })->implode("\n\n");
    }

    public function headings(): array
    {
        return ['Nombre Completo', 'Movimiento', 'Detalles de Comisión', 'Fecha Registro', 'Estatus'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 30, 'B' => 25, 'C' => 60, 'D' => 20, 'E' => 20];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function ($event) {
                $event->sheet->getStyle('C:C')
                    ->getAlignment()
                    ->setWrapText(true);
            },
        ];
    }
}