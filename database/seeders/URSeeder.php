<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class URSeeder extends Seeder
{
    public function run()
    {
        $urs = [
            ['id' => 'B00'],
            ['id' => 'C00'],
            ['id' => 'D00'],
            ['id' => 'E00'],
            ['id' => 'F00'],
            ['id' => 'G00'],
            ['id' => '100'],
            ['id' => '111'],
            ['id' => '120'],
            ['id' => '121'],
            ['id' => '122'],
            ['id' => '123'],
            ['id' => '124'],
            ['id' => '130'],
            ['id' => '131'],
            ['id' => '132'],
            ['id' => '133'],
            ['id' => '140'],
            ['id' => '141'],
            ['id' => '142'],
            ['id' => '143'],
            ['id' => '150'],
            ['id' => '151'],
            ['id' => '152'],
            ['id' => '160'],
            ['id' => '161'],
            ['id' => '162'],
            ['id' => '163'],
            ['id' => '170'],
            ['id' => '171'],
            ['id' => '172'],
            ['id' => '180'],
            ['id' => '181'],
            ['id' => '182'],
            ['id' => '200'],
            ['id' => '210'],
            ['id' => '211'],
            ['id' => '212'],
            ['id' => '220'],
            ['id' => '221'],
            ['id' => '222'],
            ['id' => '223'],
            ['id' => '231'],
        ];

        // Insertar en la tabla UR
        DB::table('ur')->insert($urs);

        $this->command->info('Datos de UR insertados correctamente.');
    }
}
