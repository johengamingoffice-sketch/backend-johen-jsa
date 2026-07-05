<?php

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_position', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('position_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_main')->default(false);
            $table->timestamps();

            $table->unique(['employee_id', 'position_id']);
        });

        // Migrate existing data: each employee with a position string gets a pivot row
        $employees = Employee::whereNotNull('position')->where('position', '!=', '')->get();
        foreach ($employees as $emp) {
            $pos = Position::where('nama', $emp->position)->first();
            if ($pos) {
                DB::table('employee_position')->insert([
                    'employee_id' => $emp->id,
                    'position_id' => $pos->id,
                    'is_main' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Add selected_position_id to leave_requests
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->foreignId('selected_position_id')->nullable()->after('employee_id')->constrained('positions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('selected_position_id');
        });

        Schema::dropIfExists('employee_position');
    }
};
