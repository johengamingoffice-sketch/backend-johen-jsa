<?php

use App\Models\Position;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $hr = Position::where('nama', 'Human Resource Generalist')->first();
        if ($hr) {
            Position::firstOrCreate(
                ['nama' => 'Resepsionis 1'],
                ['parent_id' => $hr->id, 'is_active' => true]
            );
            Position::firstOrCreate(
                ['nama' => 'Resepsionis 2'],
                ['parent_id' => $hr->id, 'is_active' => true]
            );
        }
    }

    public function down(): void
    {
        Position::whereIn('nama', ['Resepsionis 1', 'Resepsionis 2'])->delete();
    }
};
