<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcargos', function (Blueprint $table) {
            $table->increments('idsubcargo');
            $table->string('subcargo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcargos');
    }
};
