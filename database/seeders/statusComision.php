<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class statusComision extends Seeder
{
    public function run(): void
    {
        DB::table('statusComision')->insert([
            ['statusComision' => 'Solicitud', 'idModalidad' => 1, 'idtype' => '3'],
            ['statusComision' => 'Autorizada por UR', 'idModalidad' => 1, 'idtype' => '2'],
            ['statusComision' => 'Cancelación por UR', 'idModalidad' => 1, 'idtype' => '2'],
            ['statusComision' => 'En revisión de solicitud por DGPPR', 'idModalidad' => 1, 'idtype' => '1'],
            ['statusComision' => 'Comisión revisada y proceso de Pago', 'idModalidad' => 1, 'idtype' => '1'],
            ['statusComision' => 'Comisión rechazada', 'idModalidad' => 1, 'idtype' => '3', 'idtype' => '1'],
            ['statusComision' => 'En dispersión', 'idModalidad' => 1, 'idtype' => '1'],
            ['statusComision' => 'Pagada', 'idModalidad' => 1, 'idtype' => '1'],
            ['statusComision' => 'En curso', 'idModalidad' => 1, 'idtype' => '1'],
            ['statusComision' => 'En comprobación', 'idModalidad' => 1, 'idtype' => '3'],
            ['statusComision' => 'En Ampliación', 'idModalidad' => 1, 'idtype' => '2'],
            ['statusComision' => 'En revisión de comprobación por DGPPR', 'idModalidad' => 1, 'idtype' => '1'],
            ['statusComision' => 'Comprobación validada por DGPPR', 'idModalidad' => 1, 'idtype' => '1'],
            ['statusComision' => 'Terminación anticipada', 'idModalidad' => 1, 'idtype' => '1'],
            ['statusComision' => 'Finalizada', 'idModalidad' => 1, 'idtype' => '1'],
            ['statusComision' => 'Solicitud', 'idModalidad' => 2, 'idtype' => '3'],
            ['statusComision' => 'Autorizada por UR', 'idModalidad' => 2, 'idtype' => '2'],
            ['statusComision' => 'Cancelación por UR', 'idModalidad' => 2, 'idtype' => '2'],
            ['statusComision' => 'En curso', 'idModalidad' => 2, 'idtype' => '1'],
            ['statusComision' => 'En comprobación', 'idModalidad' => 2, 'idtype' => '3'],
            ['statusComision' => 'En revisión de comprobación por DGPPR', 'idModalidad' => 2, 'idtype' => '1'],
            ['statusComision' => 'Comprobación validada por DGPPR', 'idModalidad' => 2, 'idtype' => '1'],
            ['statusComision' => 'En dispersión', 'idModalidad' => 2, 'idtype' => '1'],
            ['statusComision' => 'Pagada y finalizada', 'idModalidad' => 2, 'idtype' => '1'],
            ['statusComision' => 'Comprobación autorizada por UR', 'idModalidad' => 2, 'idtype' => '2'],
            ['statusComision' => 'Comprobación autorizada por UR', 'idModalidad' => 1, 'idtype' => '2'],
        ]);
    }
}
