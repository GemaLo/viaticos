<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anulaciones', function (Blueprint $table) {
            $table->increments('idAnulacion');
            $table->string('observaciones');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users');
            $table->unsignedBigInteger('idFactura');
            $table->foreign('idFactura')->references('idFactura')->on('facturas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anulaciones');
    }
};
