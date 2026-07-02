<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('electricity_token_checks', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_check');
            $table->decimal('sisa_kwh', 10, 2);
            $table->decimal('terpakai', 10, 2)->default(0);
            $table->enum('status', ['normal', 'rendah', 'habis'])->default('normal');
            $table->foreignId('checked_by')->constrained('users');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('electricity_token_checks');
    }
};
