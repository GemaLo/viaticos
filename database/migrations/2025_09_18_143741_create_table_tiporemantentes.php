<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipoReintegro', function (Blueprint $table) {
            $table->id('idTipoReintegro');
            $table->string('tipoReintegro');
            $table->string('acronymReintegro');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipoReintegro');
    }
};
