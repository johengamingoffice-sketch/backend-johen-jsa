<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('nomor_surat')->nullable();
            $table->string('posisi_lama');
            $table->string('posisi_baru');
            $table->string('divisi_lama')->nullable();
            $table->string('divisi_baru')->nullable();
            $table->string('atasan_lama')->nullable();
            $table->string('atasan_baru')->nullable();
            $table->date('tanggal_efektif');
            $table->enum('jenis', ['promosi', 'demosi', 'mutasi'])->default('promosi');
            $table->text('alasan')->nullable();
            $table->string('pdf_path')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
