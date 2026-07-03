<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\Position;
use App\Models\PositionNote;
use Livewire\Component;

class StrukturOrganisasi extends Component
{
    public bool $showNoteModal = false;
    public ?int $selectedPositionId = null;
    public string $selectedPositionName = '';
    public string $catatan = '';
    public string $rekomendasi = '';
    public int $bulan;
    public int $tahun;
    public bool $showForm = false;
    public ?array $existingNote = null;
    public array $notesHistory = [];
    public bool $isSuperior = false;

    public function mount(): void
    {
        $this->bulan = now()->month;
        $this->tahun = now()->year;
    }

    public function openNoteModal(int $positionId): void
    {
        $this->selectedPositionId = $positionId;
        $position = Position::findOrFail($positionId);
        $this->selectedPositionName = $position->nama;
        $this->showForm = false;
        $this->loadNoteData();
        $this->dispatch('open-modal', name: 'note-modal');
    }

    public function loadNoteData(): void
    {
        if (!$this->selectedPositionId) return;

        $myPositionId = $this->getMyPositionId();

        $note = PositionNote::with('fromPosition', 'creator.employee')
            ->where('to_position_id', $this->selectedPositionId)
            ->where('bulan', $this->bulan)
            ->where('tahun', $this->tahun)
            ->first();

        $this->existingNote = $note?->toArray();
        $this->catatan = $note?->catatan ?? '';
        $this->rekomendasi = $note?->rekomendasi ?? '';

        $this->notesHistory = PositionNote::with('fromPosition', 'creator.employee')
            ->where('to_position_id', $this->selectedPositionId)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get()
            ->toArray();

        $position = Position::find($this->selectedPositionId);
        $this->isSuperior = $this->checkIsSuperior($myPositionId, $position);
    }

    private function checkIsSuperior(?int $myPositionId, ?Position $targetPosition): bool
    {
        if (!$targetPosition) return false;

        if ($myPositionId && $targetPosition->parent_id === $myPositionId) {
            return true;
        }

        $user = auth()->user();
        if (!$user) return false;

        if ($user->isSuperAdmin() || $user->isGmCeo()) {
            return true;
        }

        $posDepth = $this->getPositionDepth($targetPosition);
        $roleLevel = $user->roleLevel();

        if ($roleLevel >= 3 && $posDepth >= 3) return true;
        if ($roleLevel >= 2 && $posDepth >= 4) return true;

        return false;
    }

    private function getPositionDepth(Position $position): int
    {
        $depth = 0;
        $current = $position;
        while ($current->parent_id) {
            $depth++;
            $current = $current->parent;
            if (!$current) break;
        }
        return $depth;
    }

    public function updatedBulan(): void
    {
        $this->showForm = false;
        $this->loadNoteData();
    }

    public function updatedTahun(): void
    {
        $this->showForm = false;
        $this->loadNoteData();
    }

    public function saveNote(): void
    {
        $this->validate([
            'selectedPositionId' => ['required', 'exists:positions,id'],
            'catatan' => ['nullable', 'string', 'max:5000'],
            'rekomendasi' => ['nullable', 'string', 'max:5000'],
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', 'min:2020', 'max:2099'],
        ]);

        $myPositionId = $this->getMyPositionId();

        $position = Position::find($this->selectedPositionId);
        if (!$position) {
            $this->dispatch('notify', type: 'error', message: 'Jabatan tidak ditemukan.');
            return;
        }

        if (!$this->checkIsSuperior($myPositionId, $position)) {
            $this->dispatch('notify', type: 'error', message: 'Anda tidak berwenang memberi catatan untuk jabatan ini.');
            return;
        }

        PositionNote::updateOrCreate(
            [
                'from_position_id' => $myPositionId,
                'to_position_id' => $this->selectedPositionId,
                'bulan' => $this->bulan,
                'tahun' => $this->tahun,
            ],
            [
                'catatan' => $this->catatan,
                'rekomendasi' => $this->rekomendasi,
                'created_by' => auth()->id(),
            ]
        );

        $this->dispatch('notify', type: 'success', message: 'Catatan berhasil disimpan.');
        $this->showForm = false;
        $this->loadNoteData();
    }

    private function getMyPositionId(): ?int
    {
        $user = auth()->user();
        if (!$user || !$user->employee || !$user->employee->position) return null;

        $pos = Position::where('nama', $user->employee->position)->first();
        return $pos?->id;
    }

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

        $notesByPosition = PositionNote::selectRaw("to_position_id, COUNT(*) as total, SUM(CASE WHEN bulan = ? AND tahun = ? THEN 1 ELSE 0 END) as has_current", [now()->month, now()->year])
            ->groupBy('to_position_id')
            ->get()
            ->keyBy('to_position_id');

        $myPositionId = $this->getMyPositionId();

        return view('livewire.struktur-organisasi', [
            'roots' => $roots,
            'flatPositions' => $flatPositions,
            'notesByPosition' => $notesByPosition,
            'myPositionId' => $myPositionId,
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
