<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sinComprobantes', function (Blueprint $table) {
            $table->id('idSinComprobante');
            $table->string('conceptoPago');
            $table->date('datePay');
            $table->string('monto');
            $table->string('proof_file')->nullable();
            $table->unsignedBigInteger('idComision');
            $table->foreign('idComision')->references('idComision')->on('comisiones');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sinComprobantes');
    }
};
