<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('atasan2')->nullable()->after('atasan');
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->foreignId('atasan2_id')->nullable()->after('atasan_id')->constrained('employees')->nullOnDelete();
            $table->enum('persetujuan_atasan2', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu')->after('persetujuan_koor');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('atasan2');
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropForeign(['atasan2_id']);
            $table->dropColumn('atasan2_id');
            $table->dropColumn('persetujuan_atasan2');
        });
    }
};
