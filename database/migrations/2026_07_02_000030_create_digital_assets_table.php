<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('digital_assets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aset');
            $table->string('tagihan');
            $table->date('jatuh_tempo');
            $table->decimal('nominal', 15, 2);
            $table->enum('status', ['lunas', 'menunggu', 'terlambat'])->default('menunggu');
            $table->date('tgl_bayar')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digital_assets');
    }
};
