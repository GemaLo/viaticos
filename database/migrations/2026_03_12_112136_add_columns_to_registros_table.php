<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registros', function (Blueprint $table) {
            $table->longtext('idcomision')->nullable()->after('id_user');
            $table->integer('idstatus')->nullable()->after('idcomision');
        });
    }

    public function down(): void
    {
        Schema::table('registros', function (Blueprint $table) {
            $table->dropColumn(['idcomision', 'idstatus']);
        });
    }
};
