<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'oracle_primary';

    public function up(): void
    {
        Schema::connection('oracle_primary')->table('comisiones', function (Blueprint $table) {
            $table->boolean('rev1_estatus')->nullable()->after('nota')->comment('Estatus/Aprobación de la 1ra revisión');
            $table->timestamp('rev1_fecha')->nullable()->after('rev1_estatus')->comment('Fecha y hora de la 1ra revisión');
            $table->boolean('rev2_estatus')->nullable()->after('rev1_fecha')->comment('Estatus/Aprobación de la 2da revisión');
            $table->timestamp('rev2_fecha')->nullable()->after('rev2_estatus')->comment('Fecha y hora de la 2da revisión');
        });
    }

    public function down(): void
    {
        Schema::connection('oracle_primary')->table('comisiones', function (Blueprint $table) {
            $table->dropColumn([
                'nota',
                'rev1_estatus',
                'rev1_fecha',
                'rev2_estatus',
                'rev2_fecha',
            ]);
        });
    }
};