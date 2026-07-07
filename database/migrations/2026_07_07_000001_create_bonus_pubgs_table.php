<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bonus_pubgs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('nik');
            $table->string('nama');
            $table->decimal('ach_sold', 15, 2)->default(0);
            $table->decimal('ach_view', 15, 2)->default(0);
            $table->decimal('peak_view', 15, 2)->default(0);
            $table->decimal('insentif', 15, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bonus_pubgs');
    }
};
