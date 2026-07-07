<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'staff_host')
            ->update(['role' => 'staff_host_pubg']);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('role', 'staff_host_pubg')
            ->update(['role' => 'staff_host']);
    }
};
