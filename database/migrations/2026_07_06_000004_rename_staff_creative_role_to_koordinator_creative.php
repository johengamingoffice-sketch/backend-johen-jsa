<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'staff_creative')
            ->update(['role' => 'koordinator_creative']);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('role', 'koordinator_creative')
            ->update(['role' => 'staff_creative']);
    }
};
