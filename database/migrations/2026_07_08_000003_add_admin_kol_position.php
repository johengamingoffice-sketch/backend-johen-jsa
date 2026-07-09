<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $parent = DB::table('positions')->where('nama', 'Koordinator Creative')->first();

        DB::table('positions')->updateOrInsert(
            ['nama' => 'Admin KOL'],
            [
                'parent_id' => $parent?->id,
                'is_active' => true,
            ]
        );
    }

    public function down(): void
    {
        DB::table('positions')->where('nama', 'Admin KOL')->delete();
    }
};
