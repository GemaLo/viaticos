<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipoDeposito', function (Blueprint $table) {
            $table->id('idDeposito');
            $table->string('tipoDeposito');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipoDeposito');
    }
};
