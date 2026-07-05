<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weekly_plan_reports', function (Blueprint $table) {
            $table->text('feedback_atasan')->nullable()->after('action_plan');
        });
    }

    public function down(): void
    {
        Schema::table('weekly_plan_reports', function (Blueprint $table) {
            $table->dropColumn('feedback_atasan');
        });
    }
};
