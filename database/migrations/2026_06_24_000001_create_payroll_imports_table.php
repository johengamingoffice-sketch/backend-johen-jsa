<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_imports', function (Blueprint $table) {
            $table->id();
            $table->string('periode');
            $table->string('file_name');
            $table->integer('total_employee')->default(0);
            $table->decimal('total_payroll', 15, 2)->default(0);
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_imports');
    }
};
