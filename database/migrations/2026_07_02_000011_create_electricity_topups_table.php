<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('electricity_topups', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tanggal_bayar');
            $table->string('periode');
            $table->decimal('jumlah_kwh', 10, 2);
            $table->decimal('nominal', 15, 2);
            $table->foreignId('created_by')->constrained('users');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('electricity_topups');
    }
};
