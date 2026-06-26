<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payroll_details', function (Blueprint $table) {
            $table->dropColumn(['tunjangan', 'potongan']);
        });

        Schema::table('payroll_details', function (Blueprint $table) {
            $table->decimal('tambahan_upah', 15, 2)->default(0)->after('gaji_pokok');
            $table->decimal('bonus', 15, 2)->default(0)->after('tambahan_upah');
            $table->decimal('thr', 15, 2)->default(0)->after('bonus');
            $table->decimal('apresiasi', 15, 2)->default(0)->after('thr');
            $table->decimal('tunjangan_jabatan', 15, 2)->default(0)->after('apresiasi');
            $table->decimal('thr_dibayarkan', 15, 2)->default(0)->after('tunjangan_jabatan');
            $table->decimal('potongan_pinjaman', 15, 2)->default(0)->after('thr_dibayarkan');
            $table->decimal('potongan_absensi', 15, 2)->default(0)->after('potongan_pinjaman');
        });
    }

    public function down(): void
    {
        Schema::table('payroll_details', function (Blueprint $table) {
            $table->dropColumn([
                'tambahan_upah', 'bonus', 'thr', 'apresiasi',
                'tunjangan_jabatan', 'thr_dibayarkan',
                'potongan_pinjaman', 'potongan_absensi',
            ]);
        });

        Schema::table('payroll_details', function (Blueprint $table) {
            $table->decimal('tunjangan', 15, 2)->default(0)->after('gaji_pokok');
            $table->decimal('potongan', 15, 2)->default(0)->after('tunjangan');
        });
    }
};
