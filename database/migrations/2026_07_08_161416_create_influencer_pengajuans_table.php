<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('influencer_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->string('no_kontrak');
            $table->string('nama');
            $table->date('mulai_kontrak');
            $table->date('habis_kontrak');
            $table->string('link_sosmed')->nullable();
            $table->decimal('biaya', 15, 2)->nullable();
            $table->string('status')->default('pending_hos1');
            $table->foreignId('pengaju_id')->constrained('users');
            $table->foreignId('approved_hos1_by')->nullable()->constrained('users');
            $table->timestamp('approved_hos1_at')->nullable();
            $table->foreignId('approved_gm_by')->nullable()->constrained('users');
            $table->timestamp('approved_gm_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users');
            $table->timestamp('rejected_at')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('influencer_pengajuans');
    }
};
