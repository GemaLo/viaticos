<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            typeTransport::class,
            transportsSeeder::class,
            typeComision::class,
            destinosSeeder::class,
            typeUser::class,
            usersSeeders::class,
            ImportsSeeder::class,
            ObjetiveSeeder::class,
            ModalidadesSeeder::class,
            PartidasSeeder::class,
            statusComision::class,
            TipoDeposito::class,
            TypeFacturaSeeder::class,
            TipoReintegros::class,
            SubcargoSeeder::class
        ]);
    }
}
