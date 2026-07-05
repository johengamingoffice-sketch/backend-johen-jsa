<?php

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $hostPositions = Position::where('nama', 'like', 'Host Live (%)')->with('parent')->get();

        foreach ($hostPositions as $pos) {
            $parent = $pos->parent;
            if (!$parent) continue;

            preg_match('/\(([^)]+)\)/', $pos->nama, $sessionMatch);
            $session = $sessionMatch[1] ?? '';

            $gameName = preg_replace('/^Koordinator\s+/', '', $parent->nama);

            $oldName = $pos->nama;
            $newName = "Host {$gameName} ({$session})";

            $pos->update(['nama' => $newName]);

            Employee::where('position', $oldName)->update(['position' => $newName]);
        }
    }

    public function down(): void
    {
        $hostPositions = Position::where('nama', 'like', 'Host %(%)%')
            ->whereHas('parent', function ($q) {
                $q->where('nama', 'like', 'Koordinator%');
            })
            ->with('parent')
            ->get();

        foreach ($hostPositions as $pos) {
            preg_match('/\(([^)]+)\)/', $pos->nama, $sessionMatch);
            $session = $sessionMatch[1] ?? '';

            $oldName = $pos->nama;
            $newName = "Host Live ({$session})";

            $pos->update(['nama' => $newName]);

            Employee::where('position', $oldName)->update(['position' => $newName]);
        }
    }
};
