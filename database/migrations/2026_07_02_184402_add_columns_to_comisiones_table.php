<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comisiones', function (Blueprint $table) {
            $table->string('nivelComision')->nullable();
            $table->longtext('puestoComision')->nullable();
            $table->date('dateComprobation')->nullable();
            $table->date('dateAutorized')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('comisiones', function (Blueprint $table) {
            $table->dropColumn(['nivelComision', 'puestoComision', 'dateComprobation', 'dateAutorized']);
        });
    }
};