<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class transportsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transports')->insert([
            ['transport' => 'Avion', 'idTypeTransport' => '1'],
            ['transport' => 'Autobus', 'idTypeTransport' => '1'],
            ['transport' => 'Vehículo Oficial', 'idTypeTransport' => '2'],
            ['transport' => 'Otros', 'idTypeTransport' => '2']
        ]);
    }
}
