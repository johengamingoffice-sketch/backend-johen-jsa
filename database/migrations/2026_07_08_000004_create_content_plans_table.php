<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_plans', function (Blueprint $table) {
            $table->id();
            $table->string('posisi');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('pic')->nullable();
            $table->string('jenis_desain')->nullable();
            $table->string('link')->nullable();
            $table->date('deadline')->nullable();
            $table->string('status')->default('plan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_plans');
    }
};
