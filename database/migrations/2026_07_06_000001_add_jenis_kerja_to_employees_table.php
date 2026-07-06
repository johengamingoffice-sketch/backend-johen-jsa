<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('jenis_kerja', 30)->nullable()->after('lokasi_kerja');
            $table->string('jam_kerja')->nullable()->after('jenis_kerja');
            $table->text('jobdesk')->nullable()->after('jam_kerja');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['jenis_kerja', 'jam_kerja', 'jobdesk']);
        });
    }
};
