<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personComision', function (Blueprint $table) {
            $table->increments('idPersonComision');
            $table->string('cargo');
            $table->string('nivel');
            $table->string('rfc');
            $table->string('curp');
            $table->string('clabe');
            $table->string('cuenta');
            $table->string('banco');
            $table->string('estado_cuenta_file')->nullable();
            $table->longtext('adscripcion');
            $table->longtext('direccion');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personComision');
    }
};
