<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'staff_it')
            ->update(['role' => 'koordinator_it']);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('role', 'koordinator_it')
            ->update(['role' => 'staff_it']);
    }
};
