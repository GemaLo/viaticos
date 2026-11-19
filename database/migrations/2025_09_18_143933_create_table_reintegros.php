<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reintegros', function (Blueprint $table) {
            $table->id('idReintegro');
            $table->unsignedBigInteger('idComision');
            $table->foreign('idComision')->references('idComision')->on('comisiones');
            $table->unsignedBigInteger('idTipoReintegro');
            $table->foreign('idTipoReintegro')->references('idTipoReintegro')->on('tipoReintegro');
            $table->string('monto');
            $table->string('date')->nullable();
            $table->string('comprobante')->nullable();
            $table->string('cancelacion')->nullable();
            $table->string('observaciones')->nullable();
            $table->unsignedBigInteger('idDeposito')->nullable();
            $table->foreign('idDeposito')->references('idDeposito')->on('tipoDeposito');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reintegros');
    }
};
