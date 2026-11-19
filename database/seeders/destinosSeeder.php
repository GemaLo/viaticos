<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class destinosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('destinos')->insert([
            ['destino' => 'AGUASCALIENTES'],
            ['destino' => 'BAJA CALIFORNIA'],
            ['destino' => 'BAJA CALIFORNIA SUR'],
            ['destino' => 'CAMPECHE'],
            ['destino' => 'CHIAPAS'],
            ['destino' => 'CHIHUAHUA'],
            ['destino' => 'CIUDAD DE MÉXICO'],
            ['destino' => 'COAHUILA'],
            ['destino' => 'COLIMA'],
            ['destino' => 'DURANGO'],
            ['destino' => 'ESTADO DE MÉXICO'],
            ['destino' => 'GUANAJUATO'],
            ['destino' => 'GUERRERO'],
            ['destino' => 'HIDALGO'],
            ['destino' => 'JALISCO'],
            ['destino' => 'MICHOACÁN'],
            ['destino' => 'MORELOS'],
            ['destino' => 'NAYARIT'],
            ['destino' => 'NUEVO LEÓN'],
            ['destino' => 'OAXACA'],
            ['destino' => 'PUEBLA'],
            ['destino' => 'QUERÉTARO'],
            ['destino' => 'QUINTANA ROO'],
            ['destino' => 'SAN LUIS POTOSÍ'],
            ['destino' => 'SINALOA'],
            ['destino' => 'SONORA'],
            ['destino' => 'TABASCO'],
            ['destino' => 'TAMAULIPAS'],
            ['destino' => 'TLAXCALA'],
            ['destino' => 'VERACRUZ'],
            ['destino' => 'YUCATÁN'],
            ['destino' => 'ZACATECAS']
        ]);
    }
}
