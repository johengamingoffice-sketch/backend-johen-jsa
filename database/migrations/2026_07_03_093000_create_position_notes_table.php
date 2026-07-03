<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('position_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_position_id')->constrained('positions')->onDelete('cascade');
            $table->foreignId('to_position_id')->constrained('positions')->onDelete('cascade');
            $table->text('catatan')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->tinyInteger('bulan');
            $table->year('tahun');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['from_position_id', 'to_position_id', 'bulan', 'tahun'], 'position_note_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_notes');
    }
};
