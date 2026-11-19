<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class typeTransport extends Seeder
{
    public function run(): void
    {
        DB::table('typeTransports')->insert([
            ['type' => 'Transporte Foráneo'],
            ['type' => 'Transporte Local']
        ]);
    }
}
