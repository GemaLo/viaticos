<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsuariosExport implements 
    FromQuery, 
    WithHeadings, 
    ShouldAutoSize, 
    WithStyles, 
    WithColumnFormatting, 
    WithMapping, 
    WithEvents, 
    WithCustomStartCell
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query->orderBy('hora_entrada', 'desc');
    }

    public function startCell(): string
    {
        return 'A3'; // Empieza los encabezados en la fila 3
    }

    public function headings(): array
    {
        return [
            //"Número de empleado",
            "Nombre del Empleado",
            "Fecha y Hora de Movimiento",
            // "Fecha y Hora de Salida",
            "Movimiento",
            "Detalle"
        ];
    }

    public function map($sesion): array
    {
        return [
            //$sesion->num_empleado,
            $sesion->nombre,
            $sesion->hora_entrada,
            // $sesion->hora_salida,
            $sesion->movimiento,
            $sesion->archivo
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            3 => [ // Encabezados en fila 3
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => 'center'],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'FFF599']
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
                ],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DATETIME,
            'D' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Fila 1: Título
                $sheet->mergeCells('A1:D1');
                $sheet->setCellValue('A1', 'BITACORA DE MOVIMIENTOS DE USUARIOS');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 18],
                    'alignment' => ['horizontal' => 'center'],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Fila 2: Fecha
                $sheet->mergeCells('A2:D2');
                $sheet->setCellValue('A2', 'Generado el: ' . now()->format('d/m/Y H:i'));
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['italic' => true, 'size' => 11],
                    'alignment' => ['horizontal' => 'center'],
                ]);

                // Bordes para los datos
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle('A3:D' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                        ]
                    ]
                ]);
            }
        ];
    }
}
