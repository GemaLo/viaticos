<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoDeposito extends Seeder
{
    public function run(): void
    {
         DB::table('tipoDeposito')->insert([
            ['tipoDeposito' => 'EN VENTANILLA'],
            ['tipoDeposito' => 'POR APLICACIÓN'],
            ['tipoDeposito' => 'INTERNET']
        ]);
    }
}
