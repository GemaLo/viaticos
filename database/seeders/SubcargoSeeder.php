<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcargoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('subcargos')->insert([
            ['subcargo' => 'DIRECTOR GENERAL'],
            ['subcargo' => 'TITULAR DE UNIDAD'],
            ['subcargo' => 'SUBSECRETARIO'],
            ['subcargo' => 'SECRETARIO']
        ]);
    }
}
