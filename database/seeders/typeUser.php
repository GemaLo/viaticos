<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class typeUser extends Seeder
{
    public function run(): void
    {
        DB::table('typeUser')->insert([
            ['typeUser' => 'REVISOR'],
            ['typeUser' => 'AUTORIZADOR'],
            ['typeUser' => 'COMISIONADO']
        ]);
    }
}
