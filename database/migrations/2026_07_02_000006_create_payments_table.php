<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('payment_category_id')->constrained();
            $table->string('vendor');
            $table->decimal('amount', 15, 2);
            $table->date('payment_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('period')->nullable();
            $table->enum('status', ['lunas', 'belum_dibayar', 'tertunda', 'dibatalkan'])->default('belum_dibayar');
            $table->string('proof_file')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('asset_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
