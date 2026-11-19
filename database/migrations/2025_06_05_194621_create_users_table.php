<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id_user');
            $table->string('numEmpleado');
            $table->string('nomUsuario');
            $table->string('appUsuario');
            $table->string('apmUsuario');
            $table->string('email');
            $table->string('password');
            $table->boolean('must_change_password')->default(true);
            $table->string('active');
            $table->string('cargo');
            $table->string('unidad');
            $table->longtext('contacto')->nullable();
            $table->unsignedBigInteger('idType');
            $table->foreign('idType')->references('idType')->on('typeUser');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
