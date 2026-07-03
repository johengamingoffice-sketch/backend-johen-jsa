<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'gonzaga@johen.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        $gm = User::firstOrCreate(
            ['username' => 'gm'],
            [
                'name' => 'General Manager',
                'email' => 'gm@johen.com',
                'password' => Hash::make('password'),
                'role' => 'gm_ceo',
            ]
        );

        User::where('username', 'admin')->where('role', 'staff')->update(['role' => 'super_admin']);
        User::where('username', 'gm')->where('role', 'staff')->update(['role' => 'gm_ceo']);

        if (!$admin->employee) {
            Employee::firstOrCreate(
                ['user_id' => $admin->id],
                [
                    'nik' => 'ADM001',
                    'nama' => $admin->name,
                    'email' => $admin->email,
                    'status' => 'aktif',
                ]
            );
        }

        if (!$gm->employee) {
            Employee::firstOrCreate(
                ['user_id' => $gm->id],
                [
                    'nik' => 'GM001',
                    'nama' => $gm->name,
                    'email' => $gm->email,
                    'status' => 'aktif',
                ]
            );
        }
    }
}
