<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partidas', function (Blueprint $table) {
            $table->increments('idPartida');
            $table->string('partida');
            $table->string('concepto');
            $table->unsignedBigInteger('idTypeComision');
            $table->foreign('idTypeComision')->references('idTypeComision')->on('typeComision');
            $table->string('tipoPartida');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partidas');
    }
};
