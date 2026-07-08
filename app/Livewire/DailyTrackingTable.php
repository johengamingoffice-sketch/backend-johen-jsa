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
    public string $bulan = '';

    public function mount(): void
    {
        $this->bulan = now()->format('Y-m');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingBulan(): void
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
            $user->isKoordinatorValorant(), $user->isStaffHostValorant() => 'Valorant',
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

        if (!$employee || !$user->isManager()) {
            return view('livewire.daily-tracking-table', [
                'items' => collect(),
                'groupedItems' => collect(),
                'totalSold' => 0,
                'totalView' => 0,
                'totalPeak' => 0,
                'totalDurasi' => 0,
                'bulan' => $this->bulan,
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
                'bulan' => $this->bulan,
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
                        'bulan' => $this->bulan,
                    ]);
                }
            }
        }

        $query = BonusPubg::whereIn('bonus_pubgs.employee_id', $subordinateIds)
            ->where('bonus_pubgs.status', 'disetujui')
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('bonus_pubgs.nama', 'like', "%{$this->search}%")
                      ->orWhere('bonus_pubgs.nik', 'like', "%{$this->search}%");
                });
            })
            ->when($this->bulan, function ($q) {
                $q->whereYear('bonus_pubgs.tanggal', substr($this->bulan, 0, 4))
                  ->whereMonth('bonus_pubgs.tanggal', substr($this->bulan, 5, 2));
            })
            ->with('employee.division', 'employee.user');

        $orderRaw = "CASE
            WHEN users.role IN ('staff_host_pubg', 'koordinator_pubg') THEN 1
            WHEN users.role IN ('staff_host_ff', 'koordinator_ff') THEN 2
            WHEN users.role IN ('staff_host_mlbb', 'koordinator_mlbb') THEN 3
            WHEN users.role IN ('staff_host_efootball', 'koordinator_efootball') THEN 4
            WHEN users.role IN ('staff_host_valorant', 'koordinator_valorant') THEN 5
            ELSE 6
        END";

        $items = (clone $query)
            ->join('employees', 'bonus_pubgs.employee_id', '=', 'employees.id')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->select('bonus_pubgs.*')
            ->orderByRaw($orderRaw)
            ->latest('bonus_pubgs.tanggal')
            ->paginate(20);

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
            'bulan' => $this->bulan,
        ]);
    }
}
