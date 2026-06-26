<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_import_id')->constrained()->cascadeOnDelete();
            $table->string('nik');
            $table->string('nama');
            $table->string('email');
            $table->string('jabatan');
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('tunjangan', 15, 2);
            $table->decimal('potongan', 15, 2);
            $table->decimal('take_home_pay', 15, 2);
            $table->string('pdf_path')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_details');
    }
};
