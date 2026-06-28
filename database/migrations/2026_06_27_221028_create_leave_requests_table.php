<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->enum('jenis', ['cuti_tahunan', 'izin']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('durasi');
            $table->text('keterangan')->nullable();
            $table->enum('persetujuan_koor', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->enum('persetujuan_hr', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
