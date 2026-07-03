<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        User::where('role', 'admin')->update(['role' => 'super_admin']);
        User::where('role', 'direksi')->update(['role' => 'gm_ceo']);
        User::where('role', 'karyawan')->update(['role' => 'staff']);
    }

    public function down(): void
    {
        User::where('role', 'super_admin')->update(['role' => 'admin']);
        User::where('role', 'gm_ceo')->update(['role' => 'direksi']);
        User::where('role', 'manager')->update(['role' => 'direksi']);
        User::where('role', 'koordinator')->update(['role' => 'karyawan']);
        User::where('role', 'staff')->update(['role' => 'karyawan']);
    }
};
