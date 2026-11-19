<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comisiones', function (Blueprint $table) {
            $table->increments('idComision');
            $table->integer('folio');
            $table->integer('comision')->nullable();
            $table->string('oficio')->nullable();
            $table->integer('yearComision');
            $table->unsignedBigInteger('idPersonComision');
            $table->foreign('idPersonComision')->references('idPersonComision')->on('personComision');
            $table->unsignedBigInteger('idModalidad');
            $table->foreign('idModalidad')->references('idModalidad')->on('modalidades');
            $table->unsignedBigInteger('idTypeComision');
            $table->foreign('idTypeComision')->references('idTypeComision')->on('typeComision');
            $table->unsignedBigInteger('idStatusComision');
            $table->foreign('idStatusComision')->references('idStatusComision')->on('statusComision');
            $table->unsignedBigInteger('id_transport');
            $table->foreign('id_transport')->references('id_transport')->on('transports');
            $table->string('placas')->nullable();
            $table->string('origen');
            $table->string('destino');
            $table->integer('partidaPasaje');
            $table->integer('partidaViatico');
            $table->longtext('lugar')->nullable();
            $table->date('initialDate');
            $table->date('finalDate');
            $table->unsignedBigInteger('idObjetive')->nullable();
            $table->foreign('idObjetive')->references('idObjetive')->on('objetives');
            $table->string('daysCompletes');
            $table->string('hours');
            $table->string('clc')->nullable();
            $table->string('importe');
            $table->string('validador');
            $table->string('autorizacion')->nullable();
            $table->string('dateEndComision')->nullable();
            $table->longtext('justificacion')->nullable();
            $table->longtext('documentacionFisica')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comisiones');
    }
};
