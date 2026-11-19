<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statusComision', function (Blueprint $table) {
            $table->increments('idStatusComision');
            $table->string('statusComision');
            $table->unsignedBigInteger('idModalidad');
            $table->foreign('idModalidad')->references('idModalidad')->on('modalidades');
            $table->unsignedBigInteger('idtype');
            $table->foreign('idtype')->references('idtype')->on('typeuser')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statusComision');
    }
};
