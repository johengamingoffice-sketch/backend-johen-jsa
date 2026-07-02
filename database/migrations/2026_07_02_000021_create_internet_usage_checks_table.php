<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internet_usage_checks', function (Blueprint $table) {
            $table->id();
            $table->string('ruangan');
            $table->string('hari');
            $table->date('tanggal');
            $table->decimal('penggunaan_wifi', 10, 2)->default(0);
            $table->decimal('penggunaan_ethernet', 10, 2)->default(0);
            $table->foreignId('checked_by')->constrained('users');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internet_usage_checks');
    }
};
