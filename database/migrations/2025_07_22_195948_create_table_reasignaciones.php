<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reasignaciones', function (Blueprint $table) {
            $table->id('idReasignacion');
            $table->date('dateReasignacionInicial')->nullable();
            $table->date('dateReasignacionFinal')->nullable();
            $table->date('dateReturn')->nullable();
            $table->longtext('motivoAmpliacion')->nullable();
            $table->longtext('motivo')->nullable();
            $table->string('newImport')->nullable();
            $table->string('returnImport')->nullable();
            $table->string('comprobante')->nullable();
            $table->unsignedBigInteger('idComision');
            $table->foreign('idComision')->references('idComision')->on('comisiones');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reasignaciones');
    }
};
