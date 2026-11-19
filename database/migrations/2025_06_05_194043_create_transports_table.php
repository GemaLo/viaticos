<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transports', function (Blueprint $table) {
            $table->increments('id_transport');
            $table->string('transport');
            $table->unsignedBigInteger('idTypeTransport');
            $table->foreign('idTypeTransport')->references('idTypeTransport')->on('typeTransports');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('transports');
    }
};
