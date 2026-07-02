<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internet_payments', function (Blueprint $table) {
            $table->id();
            $table->string('nama_internet');
            $table->string('provider');
            $table->string('pic');
            $table->string('jabatan')->nullable();
            $table->date('masa_tenggang');
            $table->decimal('biaya', 15, 2);
            $table->enum('status', ['lunas', 'menunggu', 'terlambat'])->default('menunggu');
            $table->date('tgl_bayar')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internet_payments');
    }
};
