<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_plan_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_plan_id')->constrained()->cascadeOnDelete();
            $table->integer('week');
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('comment')->default(0);
            $table->integer('save')->default(0);
            $table->integer('share')->default(0);
            $table->integer('followers')->default(0);
            $table->timestamps();

            $table->unique(['content_plan_id', 'week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_plan_reports');
    }
};
