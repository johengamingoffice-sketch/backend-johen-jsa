<?php

namespace App\Livewire;

use App\Models\BonusPubg;
use App\Models\Employee;
use App\Models\Position;
use Livewire\Component;
use Livewire\WithPagination;

class DailyTrackingTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $date = '';

    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingDate(): void
    {
        $this->resetPage();
    }

    private function getSubordinateIds(Employee $employee): array
    {
        $position = $employee->mainPosition();
        if (!$position) return [];

        $descendantIds = $this->getDescendantIds($position->id);
        $descendantIds = array_diff($descendantIds, [$position->id]);

        if (empty($descendantIds)) return [];

        return Employee::whereIn('id', function ($q) use ($descendantIds) {
            $q->select('employee_id')
              ->from('employee_position')
              ->whereIn('position_id', $descendantIds);
        })->pluck('id')->toArray();
    }

    private function getDescendantIds(int $positionId): array
    {
        $ids = [$positionId];
        $children = Position::where('parent_id', $positionId)->pluck('id')->toArray();
        foreach ($children as $childId) {
            $ids = array_merge($ids, $this->getDescendantIds($childId));
        }
        return $ids;
    }

    private function getDivisi(Employee $employee): string
    {
        $user = $employee->user;
        if (!$user) return $employee->division?->nama ?? '-';

        return match (true) {
            $user->isKoordinatorPubg(), $user->isStaffHostPubg() => 'PUBG',
            $user->isKoordinatorFf(), $user->isStaffHostFf() => 'Free Fire',
            $user->isKoordinatorMlbb(), $user->isStaffHostMlbb() => 'MLBB',
            $user->isKoordinatorEfootball(), $user->isStaffHostEfootball() => 'E-football',
            default => $employee->division?->nama ?? '-',
        };
    }

    private function getEfootballEmployeeIds(): array
    {
        $efootball = Position::where('nama', 'Koordinator E-football')->first();
        if (!$efootball) return [];

        $positionIds = $this->getDescendantIds($efootball->id);

        return Employee::whereHas('positions', function ($q) use ($positionIds) {
            $q->whereIn('position_id', $positionIds);
        })->pluck('id')->toArray();
    }

    private function isHeadOfStore2(Employee $employee): bool
    {
        $position = $employee->mainPosition();
        if (!$position) return false;

        return str_contains(strtolower($position->nama), 'head of store 2');
    }

    public function render()
    {
        $user = auth()->user();
        $employee = $user->employee;
        $today = $this->date;

        if (!$employee || !$user->isManager()) {
            return view('livewire.daily-tracking-table', [
                'items' => collect(),
                'groupedItems' => collect(),
                'totalSold' => 0,
                'totalView' => 0,
                'totalPeak' => 0,
                'totalDurasi' => 0,
                'today' => $today,
            ]);
        }

        $subordinateIds = $this->getSubordinateIds($employee);
        if (empty($subordinateIds)) {
            return view('livewire.daily-tracking-table', [
                'items' => collect(),
                'groupedItems' => collect(),
                'totalSold' => 0,
                'totalView' => 0,
                'totalPeak' => 0,
                'totalDurasi' => 0,
                'today' => $today,
            ]);
        }

        if ($this->isHeadOfStore2($employee)) {
            $efootballIds = $this->getEfootballEmployeeIds();
            if (!empty($efootballIds)) {
                $subordinateIds = array_diff($subordinateIds, $efootballIds);
                if (empty($subordinateIds)) {
                    return view('livewire.daily-tracking-table', [
                        'items' => collect(),
                        'groupedItems' => collect(),
                        'totalSold' => 0,
                        'totalView' => 0,
                        'totalPeak' => 0,
                        'totalDurasi' => 0,
                        'today' => $today,
                    ]);
                }
            }
        }

        $query = BonusPubg::whereIn('employee_id', $subordinateIds)
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('nama', 'like', "%{$this->search}%")
                      ->orWhere('nik', 'like', "%{$this->search}%");
                });
            })
            ->when($this->date, function ($q) {
                $q->whereDate('tanggal', $this->date);
            })
            ->with('employee.division', 'employee.user');

        $items = (clone $query)->latest('tanggal')->paginate(20);

        $groupedItems = $items->getCollection()->groupBy(function ($item) {
            return $item->tanggal->format('Y-m-d');
        });

        $groupedItems->transform(function ($dateItems) {
            return $dateItems->map(function ($item) {
                $emp = $item->employee;
                $item->divisi = $emp ? $this->getDivisi($emp) : '-';
                return $item;
            });
        });

        $allItems = (clone $query)->get();
        $totalSold = $allItems->sum('ach_sold');
        $totalView = $allItems->sum('ach_view');
        $totalPeak = $allItems->sum('peak_view');
        $totalDurasi = $allItems->sum('durasi');

        return view('livewire.daily-tracking-table', [
            'items' => $items,
            'groupedItems' => $groupedItems,
            'totalSold' => $totalSold,
            'totalView' => $totalView,
            'totalPeak' => $totalPeak,
            'totalDurasi' => $totalDurasi,
            'today' => $today,
        ]);
    }
}
