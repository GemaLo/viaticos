<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoReintegros extends Seeder
{
    public function run(): void
    {
        DB::table('tipoReintegro')->insert([
            ['tipoReintegro' => 'POR CANCELACIÓN TOTAL', 'acronymReintegro' => 'PCT'],
            ['tipoReintegro' => 'POR TERMINACIÓN ANTICIPADA', 'acronymReintegro' => 'PTA'],
            ['tipoReintegro' => 'POR REMANENTE', 'acronymReintegro' => 'PR']
        ]);
    }
}
