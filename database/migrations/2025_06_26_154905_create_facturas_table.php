<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->increments('idFactura');
            $table->string('cfdi')->unique();
            $table->string('rfcEmisor');
            $table->string('rfcReceptor');
            $table->decimal('importe', 10, 2);
            $table->date('dateFactura');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('propina', 10, 2)->nullable();
            $table->decimal('iva', 10, 2);
            $table->decimal('ish', 10, 2);
            $table->decimal('ieps', 10, 2);
            $table->decimal('retenIva', 10, 2);
            $table->decimal('retenIsh', 10, 2);
            $table->string('regimenReceptor');
            $table->string('regimenEmisor');
            $table->string('formaPago');
            $table->string('cp');
            $table->text('descripcion');
            $table->string('status')->nullable();
            $table->unsignedBigInteger('idComision');
            $table->foreign('idComision')->references('idComision')->on('comisiones');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users');
            $table->boolean('cancelacion')->nullable();
            $table->text('evidenciaRequerida')->nullable();
            $table->text('observaciones')->nullable();
            $table->text('lecturaPdf')->nullable();
            $table->unsignedInteger('id_factura_reemplazo')->nullable();
            $table->foreign('id_factura_reemplazo')->references('idFactura')->on('facturas');
            $table->unsignedInteger('idTypeFactura')->nullable();
            $table->foreign('idTypeFactura')->references('idTypeFactura')->on('typeFactura');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};