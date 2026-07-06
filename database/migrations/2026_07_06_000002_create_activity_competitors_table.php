<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_competitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('jenis'); // swot / monitoring
            $table->date('tanggal_analysis')->nullable();
            $table->string('nama_competitor')->nullable();
            $table->string('platform')->nullable(); // instagram / tiktok
            $table->string('kategori_produk')->nullable(); // top_up_game / jual_beli_akun_game
            $table->string('link')->nullable();
            $table->text('strength')->nullable();
            $table->text('weakness')->nullable();
            $table->text('opportunity')->nullable();
            $table->text('threat')->nullable();
            $table->text('kesimpulan')->nullable();
            $table->text('feedback_atasan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_competitors');
    }
};
