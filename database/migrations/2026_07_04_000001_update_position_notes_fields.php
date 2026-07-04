<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('position_notes', function (Blueprint $table) {
            $table->dropColumn(['catatan', 'rekomendasi']);
        });

        Schema::table('position_notes', function (Blueprint $table) {
            $table->text('situasi')->nullable()->after('to_position_id');
            $table->text('catatan')->nullable()->after('situasi');
            $table->text('komitmen')->nullable()->after('catatan');
            $table->text('rekomendasi_jenjang')->nullable()->after('komitmen');
        });
    }

    public function down(): void
    {
        Schema::table('position_notes', function (Blueprint $table) {
            $table->dropColumn(['situasi', 'catatan', 'komitmen', 'rekomendasi_jenjang']);
        });

        Schema::table('position_notes', function (Blueprint $table) {
            $table->text('catatan')->nullable();
            $table->text('rekomendasi')->nullable();
        });
    }
};
