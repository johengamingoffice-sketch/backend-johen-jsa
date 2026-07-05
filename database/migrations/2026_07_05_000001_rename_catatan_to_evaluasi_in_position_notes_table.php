<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('position_notes', function (Blueprint $table) {
            $table->renameColumn('catatan', 'evaluasi');
        });
    }

    public function down(): void
    {
        Schema::table('position_notes', function (Blueprint $table) {
            $table->renameColumn('evaluasi', 'catatan');
        });
    }
};
