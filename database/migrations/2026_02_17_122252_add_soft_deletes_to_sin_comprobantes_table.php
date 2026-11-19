<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sinComprobantes', function (Blueprint $table) {
            $table->integer('activo')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sinComprobantes', function (Blueprint $table) {
            $table->integer('activo')->nullable();
        });
    }
};
