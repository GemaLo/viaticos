<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeFacturaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('typeFactura')->insert([
            ['typeFactura' => 'HOSPEDAJE'],
            ['typeFactura' => 'INTERNACIONAL'],
            ['typeFactura' => 'ALIMENTOS'],
            ['typeFactura' => 'TRANSPORTE FORANEO'],
            ['typeFactura' => 'TRANSPORTE LOCAL'],
            ['typeFactura' => 'HOSPEDAJE Y ALIMENTOS'],
            ['typeFactura' => 'PEAJES']
        ]);
    }
}
