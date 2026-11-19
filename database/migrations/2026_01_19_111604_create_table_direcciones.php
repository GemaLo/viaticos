<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::create('direcciones', function (Blueprint $table) {
            $table->increments('idDireccion');
            $table->string('no_direccion');
            $table->string('nombre_ur');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('direcciones');

    }
};
