<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('typeComision', function (Blueprint $table) {
            $table->increments('idTypeComision');
            $table->string('typeComision');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('typeComision');
    }
};
