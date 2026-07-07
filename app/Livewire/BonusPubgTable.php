<?php

namespace App\Livewire;

use App\Models\BonusPubg;
use App\Models\Employee;
use App\Models\Position;
use Livewire\Component;
use Livewire\WithPagination;

class BonusPubgTable extends Component
{
    use WithPagination;

    public string $tab = 'saya';
    public string $search = '';
    public string $bulan = '';

    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public ?int $editId = null;
    public bool $showDeleteConfirm = false;
    public ?int $deleteId = null;

    public string $tanggal = '';
    public string $nik = '';
    public string $nama = '';
    public string $sesi = '';
    public string $ach_sold = '';
    public string $ach_view = '';
    public string $peak_view = '';
    public string $insentif = '';
    public string $catatan = '';

    protected $updatesQueryString = ['search'];

    protected function rules(): array
    {
        return [
            'tanggal' => 'required|date',
            'nik' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'sesi' => 'required|string|in:Pagi,Siang,Malam,Subuh',
            'ach_sold' => 'required|numeric|min:0',
            'ach_view' => 'required|numeric|min:0',
            'peak_view' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ];
    }

    protected function messages(): array
    {
        return [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nama.required' => 'Nama wajib diisi.',
            'sesi.required' => 'Sesi wajib diisi.',
            'ach_sold.required' => 'Sold wajib diisi.',
            'ach_view.required' => 'View wajib diisi.',
            'peak_view.required' => 'Peak View wajib diisi.',
        ];
    }

    public function updatedNik(string $value): void
    {
        if (!$value) return;
        $employee = Employee::where('nik', $value)->first();
        if ($employee) {
            $this->nama = $employee->nama;
        }
    }

    public function updatedAchSold(string $value): void
    {
        $value = str_replace(',', '.', $value);
        if (is_numeric($value) && $value > 0) {
            $this->insentif = (string) ($value * 100000);
        } else {
            $this->insentif = '0';
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingBulan(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal(int $id): void
    {
        $item = BonusPubg::findOrFail($id);
        $this->editId = $item->id;
        $this->tanggal = $item->tanggal->format('Y-m-d');
        $this->nik = $item->nik;
        $this->nama = $item->nama;
        $this->sesi = $item->sesi ?? '';
        $this->ach_sold = (string) $item->ach_sold;
        $this->ach_view = (string) $item->ach_view;
        $this->peak_view = (string) $item->peak_view;
        $this->insentif = (string) ($item->ach_sold * 100000);
        $this->catatan = $item->catatan ?? '';
        $this->showEditModal = true;
    }

    public function closeModal(): void
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->editId = null;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $this->validate();

        $employee = Employee::where('nik', $this->nik)->first();
        if (!$employee) {
            $this->addError('nik', 'Karyawan dengan NIK tersebut tidak ditemukan.');
            return;
        }

        $sold = str_replace(',', '.', $this->ach_sold);

        BonusPubg::create([
            'employee_id' => $employee->id,
            'tanggal' => $this->tanggal,
            'nik' => $this->nik,
            'nama' => $this->nama,
            'sesi' => $this->sesi,
            'ach_sold' => $sold,
            'ach_view' => str_replace(',', '.', $this->ach_view),
            'peak_view' => str_replace(',', '.', $this->peak_view),
            'insentif' => $sold * 100000,
            'catatan' => $this->catatan ?: null,
        ]);

        $this->closeModal();
        $this->dispatch('notify', type: 'success', message: 'Data bonus berhasil ditambahkan.');
    }

    public function update(): void
    {
        $this->validate();
        $sold = str_replace(',', '.', $this->ach_sold);
        $item = BonusPubg::findOrFail($this->editId);
        $item->update([
            'tanggal' => $this->tanggal,
            'nik' => $this->nik,
            'nama' => $this->nama,
            'sesi' => $this->sesi,
            'ach_sold' => $sold,
            'ach_view' => str_replace(',', '.', $this->ach_view),
            'peak_view' => str_replace(',', '.', $this->peak_view),
            'insentif' => $sold * 100000,
            'catatan' => $this->catatan ?: null,
        ]);

        $this->closeModal();
        $this->dispatch('notify', type: 'success', message: 'Data bonus berhasil diperbarui.');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteConfirm = true;
    }

    public function executeDelete(): void
    {
        if (!$this->deleteId) return;
        BonusPubg::findOrFail($this->deleteId)->delete();
        $this->dispatch('notify', type: 'success', message: 'Data bonus berhasil dihapus.');
        $this->cancelDelete();
    }

    public function cancelDelete(): void
    {
        $this->showDeleteConfirm = false;
        $this->deleteId = null;
    }

    public function getDivisiName(): string
    {
        $user = auth()->user();

        return match (true) {
            $user->isKoordinatorMlbb(), $user->isStaffHostMlbb() => 'MLBB',
            $user->isKoordinatorEfootball(), $user->isStaffHostEfootball() => 'E-football',
            $user->isKoordinatorFf(), $user->isStaffHostFf() => 'Free Fire',
            $user->isKoordinatorPubg(), $user->isStaffHostPubg() => 'PUBG',
            default => 'PUBG',
        };
    }

    public function render()
    {
        $user = auth()->user();
        $userEmployee = $user->employee;

        $query = BonusPubg::query();

        if ($user->isKoordinatorGame()) {
            if ($userEmployee) {
                if ($this->tab === 'saya') {
                    $query->where('employee_id', $userEmployee->id);
                } else {
                    $subordinateIds = $this->getSubordinateEmployeeIds();
                    if (!empty($subordinateIds)) {
                        $query->whereIn('employee_id', $subordinateIds);
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                }
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($userEmployee) {
            $query->where('employee_id', $userEmployee->id);
        }

        $items = $query->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('nama', 'like', "%{$this->search}%")
                  ->orWhere('nik', 'like', "%{$this->search}%");
            });
        })
        ->when($this->bulan, function ($query) {
            $query->whereYear('tanggal', substr($this->bulan, 0, 4))
                  ->whereMonth('tanggal', substr($this->bulan, 5, 2));
        })
        ->latest('tanggal')
        ->paginate(10);

        $employees = Employee::orderBy('nama')->get();

        $totalSold = 0;
        $totalView = 0;
        $totalPeak = 0;
        $totalBonus = 0;
        if (($user->isStaffHostPubg() || $user->isStaffHostFf() || $user->isStaffHostMlbb() || $user->isStaffHostEfootball() || $user->isKoordinatorGame()) && $userEmployee) {
            $statsQuery = BonusPubg::query();
            if ($this->tab === 'saya') {
                $statsQuery->where('employee_id', $userEmployee->id);
            } elseif ($user->isKoordinatorGame()) {
                $subordinateIds = $this->getSubordinateEmployeeIds();
                if (!empty($subordinateIds)) {
                    $statsQuery->whereIn('employee_id', $subordinateIds);
                } else {
                    $statsQuery->whereRaw('1 = 0');
                }
            } else {
                $statsQuery->where('employee_id', $userEmployee->id);
            }
            $totalSold = (clone $statsQuery)->sum('ach_sold');
            $totalView = (clone $statsQuery)->sum('ach_view');
            $totalPeak = (clone $statsQuery)->sum('peak_view');
            $totalBonus = (clone $statsQuery)->sum('insentif');
        }

        $divisi = $this->getDivisiName();

        return view('livewire.bonus-pubg-table', compact('items', 'employees', 'totalSold', 'totalView', 'totalPeak', 'totalBonus', 'divisi'));
    }

    private function getSubordinateEmployeeIds(): array
    {
        $employee = auth()->user()->employee;
        if (!$employee) return [];

        $mainPosition = $employee->mainPosition();
        if (!$mainPosition) return [];

        $descendantIds = $this->getAllDescendantIds($mainPosition);
        if (empty($descendantIds)) return [];

        return Employee::whereHas('positions', function ($q) use ($descendantIds) {
            $q->whereIn('position_id', $descendantIds);
        })->pluck('id')->toArray();
    }

    private function getAllDescendantIds(Position $position): array
    {
        $ids = [];
        $children = Position::where('parent_id', $position->id)->get();
        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getAllDescendantIds($child));
        }
        return $ids;
    }

    private function resetForm(): void
    {
        $this->editId = null;
        $this->tanggal = now()->format('Y-m-d');
        $this->nik = '';
        $this->nama = '';
        $this->sesi = '';
        $this->ach_sold = '';
        $this->ach_view = '';
        $this->peak_view = '';
        $this->insentif = '';
        $this->catatan = '';
        $this->resetErrorBag();
    }
}
