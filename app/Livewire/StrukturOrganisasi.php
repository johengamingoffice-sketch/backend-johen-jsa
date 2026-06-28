<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\Position;
use Livewire\Component;

class StrukturOrganisasi extends Component
{
    public function render()
    {
        $allPositions = Position::where('is_active', true)->get();

        $employeesByPosition = Employee::whereNotNull('position')
            ->get()
            ->groupBy('position');

        $roots = $this->buildTree($allPositions, null, $employeesByPosition);

        $flatPositions = $allPositions->mapWithKeys(function ($pos) use ($employeesByPosition) {
            $emp = $employeesByPosition->get($pos->nama)?->first();
            return [$pos->id => [
                'id' => $pos->id,
                'nama' => $pos->nama,
                'parent_id' => $pos->parent_id,
                'employee_nama' => $emp?->nama,
                'employee_nik' => $emp?->nik,
                'employee_foto' => $emp?->foto,
            ]];
        });

        return view('livewire.struktur-organisasi', [
            'roots' => $roots,
            'flatPositions' => $flatPositions,
        ]);
    }

    private function buildTree($positions, $parentId, $employeesByPosition)
    {
        return $positions
            ->where('parent_id', $parentId)
            ->map(function ($pos) use ($positions, $employeesByPosition) {
                return [
                    'id' => $pos->id,
                    'nama' => $pos->nama,
                    'employee' => $employeesByPosition->get($pos->nama)?->first(),
                    'children' => $this->buildTree($positions, $pos->id, $employeesByPosition),
                ];
            })->values();
    }
}
