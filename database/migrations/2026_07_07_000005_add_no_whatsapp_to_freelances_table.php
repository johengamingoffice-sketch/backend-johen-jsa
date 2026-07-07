<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('freelances', function (Blueprint $table) {
            $table->string('no_whatsapp', 20)->nullable()->after('host_position');
        });
    }

    public function down(): void
    {
        Schema::table('freelances', function (Blueprint $table) {
            $table->dropColumn('no_whatsapp');
        });
    }
};
