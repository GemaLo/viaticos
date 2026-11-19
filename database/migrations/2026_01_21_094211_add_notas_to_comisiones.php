<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('comisiones', function (Blueprint $table) {
            // 'after' posiciona la columna, 'nullable' permite valores nulos
            $table->string('nota')->nullable()->after('documentacionfisica');
        });
    }

    public function down()
    {
        Schema::table('comisiones', function (Blueprint $table) {
            $table->dropColumn('notas');
        });
    }
};
