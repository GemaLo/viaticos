<?php

namespace App\Imports;

use App\Models\Beneficiario;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Illuminate\Support\Collection;

class BeneficiariosImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure, WithEvents
{
    use SkipsFailures;

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                Beneficiario::truncate();
            },
        ];
    }

    public function rules(): array
    {
        return [
            'rfc' => [
                'required',
                'min:10',
            ],

            'activo' => [
                'required',
                'in:S',
            ],

            'razon_social' => 'required|string',
            'id_ramo' => 'nullable|integer',
            'codigo_ben' => 'nullable|string',
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $data = [
                'razon_social'        => $row['razon_social'] ?? null,
                'activo'              => $row['activo'] ?? null,
                'id_ramo'             => $row['id_ramo'] ?? null,
                'codigo_beneficiario' => $row['codigo_beneficiario'] ?? null,
                'rfc'                 => $row['rfc'] ?? null,
            ];

            Beneficiario::updateOrCreate(['rfc' => $data['rfc']], $data);
        }
    }

    public function customValidationMessages()
    {
        return [
            'rfc.min' => 'El RFC debe tener al menos 10 caracteres.',
            'activo.in' => 'Solo se permiten registros con "S" en la columna ACTIVO.',
        ];
    }
}