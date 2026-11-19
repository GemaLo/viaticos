<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beneficiarios', function (Blueprint $table) {
            $table->id('idBeneficiario');
            $table->string('rfc')->nullable();
            $table->string('razon_social')->nullable();
            $table->string('fecha_alta')->nullable();
            $table->string('activo')->nullable();
            $table->string('id_ramo')->nullable();
            $table->string('codigo_beneficiario')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiarios');
    }
};
