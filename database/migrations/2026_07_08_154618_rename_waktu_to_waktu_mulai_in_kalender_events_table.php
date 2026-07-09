<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kalender_events', function (Blueprint $table) {
            $table->renameColumn('waktu', 'waktu_mulai');
            $table->string('waktu_selesai')->nullable()->after('waktu_mulai');
        });
    }

    public function down(): void
    {
        Schema::table('kalender_events', function (Blueprint $table) {
            $table->dropColumn('waktu_selesai');
            $table->renameColumn('waktu_mulai', 'waktu');
        });
    }
};
