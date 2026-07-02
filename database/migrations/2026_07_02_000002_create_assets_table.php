<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('category_id')->constrained('asset_categories');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->string('supplier')->nullable();
            $table->string('location')->nullable();
            $table->enum('condition', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->enum('status', ['tersedia', 'dipinjam', 'dalam_perbaikan', 'dihapuskan'])->default('tersedia');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
