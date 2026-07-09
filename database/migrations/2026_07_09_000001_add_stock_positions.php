<?php

use App\Models\Position;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $hos1 = Position::where('nama', 'Head of Store 1')->first();
        if (!$hos1) return;

        $koorStock = Position::firstOrCreate(
            ['nama' => 'Koordinator Stock'],
            ['parent_id' => $hos1->id, 'is_active' => true]
        );

        Position::firstOrCreate(
            ['nama' => 'Admin Record Johen & Monkey PUBG'],
            ['parent_id' => $koorStock->id, 'is_active' => true]
        );

        Position::firstOrCreate(
            ['nama' => 'Admin Record FF, ML, E-Football'],
            ['parent_id' => $koorStock->id, 'is_active' => true]
        );
    }

    public function down(): void
    {
        Position::whereIn('nama', ['Admin Stock 1', 'Admin Stock 2', 'Koordinator Stock'])->delete();
    }
};
