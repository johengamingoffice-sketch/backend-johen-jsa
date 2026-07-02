<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('jenis');
            $table->string('detail');
            $table->decimal('nominal', 15, 2);
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'lunas', 'jatuh_tempo'])->default('menunggu');
            $table->date('tgl_bayar')->nullable();
            $table->string('bukti')->nullable();
            $table->foreignId('pengaju_id')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_submissions');
    }
};
