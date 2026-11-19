<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartidasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('partidas')->insert(['partida' => '37201', 'concepto' => strtoupper('Pasajes terrestres nacionales para labores en campo y de supervisión'), 'idTypeComision' => '1', 'tipoPartida' => 'PASAJE']);
        DB::table('partidas')->insert(['partida' => '37202', 'concepto' => strtoupper('Pasajes terrestres nacionales asociados a los programas de seguridad pública y nacional'), 'idTypeComision' => '1', 'tipoPartida' => 'PASAJE']);
        DB::table('partidas')->insert(['partida' => '37203', 'concepto' => strtoupper('Pasajes terrestres nacionales asociados a desastres naturales'), 'idTypeComision' => '1', 'tipoPartida' => 'PASAJE']);
        DB::table('partidas')->insert(['partida' => '37204', 'concepto' => strtoupper('Pasajes terrestres nacionales para servidores públicos de mando en el desempeño de comisiones y funciones oficiales'), 'idTypeComision' => '1', 'tipoPartida' => 'PASAJE']);

        DB::table('partidas')->insert(['partida' => '37501', 'concepto' => strtoupper('Viáticos nacionales para labores en campo y de supervisión'), 'idTypeComision' => '1', 'tipoPartida' => 'VIATICO']);
        DB::table('partidas')->insert(['partida' => '37502', 'concepto' => strtoupper('Viáticos nacionales asociados a los programas de seguridad pública y nacional'), 'idTypeComision' => '1', 'tipoPartida' => 'VIATICO']);
        DB::table('partidas')->insert(['partida' => '37503', 'concepto' => strtoupper('Viáticos nacionales asociados a desastres naturales'), 'idTypeComision' => '1', 'tipoPartida' => 'VIATICO']);
        DB::table('partidas')->insert(['partida' => '37504', 'concepto' => strtoupper('Viáticos nacionales para servidores públicos en el desempeño de funciones oficiales'), 'idTypeComision' => '1', 'tipoPartida' => 'VIATICO']);
        
        DB::table('partidas')->insert(['partida' => '37205', 'concepto' => strtoupper('Pasajes terrestres internacionales asociados a los programas de seguridad pública y nacional'), 'idTypeComision' => '2', 'tipoPartida' => 'PASAJE']);
        DB::table('partidas')->insert(['partida' => '37206', 'concepto' => strtoupper('Pasajes terrestres internacionales para servidores públicos en el desempeño de comisiones y funciones oficiales'), 'idTypeComision' => '2', 'tipoPartida' => 'PASAJE']);

        DB::table('partidas')->insert(['partida' => '37601', 'concepto' => strtoupper('Viáticos en el extranjero asociados a los programas de seguridad pública y nacional'), 'idTypeComision' => '2', 'tipoPartida' => 'VIATICO']);
        DB::table('partidas')->insert(['partida' => '37602', 'concepto' => strtoupper('Viáticos en el extranjero para servidores públicos en el desempeño de comisiones y funciones oficiales'), 'idTypeComision' => '2', 'tipoPartida' => 'VIATICO']);
    }
}
