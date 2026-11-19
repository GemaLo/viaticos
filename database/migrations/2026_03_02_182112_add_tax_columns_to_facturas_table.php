<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->string('isr')->nullable()->after('iva_percent');
            $table->string('impuestos_locales')->nullable()->after('isr');
            $table->string('otros')->nullable()->after('impuestos_locales');
        });
    }

    public function down()
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn(['isr', 'impuestos_locales', 'otros']);
        });
    }
};
