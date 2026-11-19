<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('imports')->insert(['personNivel' => '11', 'completeImport' => '980', 'mediumImport' => '490']);
        DB::table('imports')->insert(['personNivel' => '21', 'completeImport' => '980', 'mediumImport' => '490']);
        DB::table('imports')->insert(['personNivel' => '23', 'completeImport' => '980', 'mediumImport' => '490']);
        DB::table('imports')->insert(['personNivel' => 'G11', 'completeImport' => '2850', 'mediumImport' => '1425']);
        DB::table('imports')->insert(['personNivel' => 'J11', 'completeImport' => '2850', 'mediumImport' => '1425']);
        DB::table('imports')->insert(['personNivel' => 'J21', 'completeImport' => '2850', 'mediumImport' => '1425']);
        DB::table('imports')->insert(['personNivel' => 'K21', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'K22', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'K31', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'M11', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'M21', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'M23', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'M31', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'M33', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'M34', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'M41', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'M43', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'N11', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'N13', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'N21', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'N22', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'N23', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'N31', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'N33', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'O11', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'O13', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'O21', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'O23', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'O32', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'O33', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'Operativo', 'completeImport' => '980', 'mediumImport' => '490']);
        DB::table('imports')->insert(['personNivel' => 'P11', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'P12', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'P13', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'P23', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'P31', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'P32', 'completeImport' => '1700', 'mediumImport' => '850']);
        DB::table('imports')->insert(['personNivel' => 'P33', 'completeImport' => '1700', 'mediumImport' => '850']);
    }
}
