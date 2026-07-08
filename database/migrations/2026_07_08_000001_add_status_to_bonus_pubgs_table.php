<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bonus_pubgs', function (Blueprint $table) {
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('disetujui')->after('catatan');
            $table->foreignId('approved_by')->nullable()->constrained('employees')->nullOnDelete()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('bonus_pubgs', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['status', 'approved_by']);
        });
    }
};
