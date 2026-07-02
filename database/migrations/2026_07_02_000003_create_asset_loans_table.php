<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained();
            $table->date('loan_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam');
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_loans');
    }
};
