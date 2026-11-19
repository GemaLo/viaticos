<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModalidadesSeeder extends Seeder
{
    public function run(): void
    {
         DB::table('modalidades')->insert([
            ['modalidad' => 'ANTICIPADA', 'acronym' => 'VA'],
            ['modalidad' => 'DEVENGADA', 'acronym' => 'VD']
        ]);
    }
}
