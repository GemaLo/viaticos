<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historialEmployee', function (Blueprint $table) {
            $table->id('idHistorialEmployee');
            $table->string('origin', 255);
            $table->string('numero_anterior', 255);
            $table->string('numero_nuevo', 255);
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('historialEmployee');
    }
};
