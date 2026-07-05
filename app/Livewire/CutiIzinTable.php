<?php

namespace App\Livewire;

use App\Models\LeaveRequest;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class CutiIzinTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterJenis = '';
    public string $filterStatus = '';

    public bool $showPengajuanModal = false;
    public ?int $selectedPositionId = null;
    public string $pengajuanJenis = 'cuti_tahunan';
    public string $pengajuanTanggalMulai = '';
    public string $pengajuanTanggalSelesai = '';
    public string $pengajuanKeterangan = '';

    public bool $showPinModal = false;
    public bool $showNoPinModal = false;
    public bool $showAtasan2ErrorModal = false;
    public string $atasan2ErrorMessage = '';
    public bool $showDeleteConfirmModal = false;
    public ?int $deleteId = null;
    public string $pin = '';
    public string $catatan = '';
    public ?int $pendingId = null;
    public string $pendingLevel = '';
    public string $pendingAction = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openPengajuanModal(): void
    {
        $this->showPengajuanModal = true;
        $this->pengajuanJenis = 'cuti_tahunan';
        $this->pengajuanTanggalMulai = '';
        $this->pengajuanTanggalSelesai = '';
        $this->pengajuanKeterangan = '';
        $mainPos = auth()->user()->employee?->mainPosition();
        $this->selectedPositionId = $mainPos?->id;
        $this->resetErrorBag();
    }

    public function closePengajuanModal(): void
    {
        $this->showPengajuanModal = false;
        $this->resetErrorBag();
    }

    public function submitPengajuan(): void
    {
        $rules = [
            'pengajuanJenis' => ['required', 'in:cuti_tahunan,izin'],
            'pengajuanTanggalMulai' => ['required', 'date'],
            'pengajuanTanggalSelesai' => ['required', 'date', 'after_or_equal:pengajuanTanggalMulai'],
            'pengajuanKeterangan' => ['required', 'string', 'max:1000'],
        ];

        $employee = auth()->user()->employee;

        if ($employee && $employee->hasMultiplePositions()) {
            $rules['selectedPositionId'] = ['required', 'exists:positions,id'];
        }

        $this->validate($rules);

        $user = auth()->user();

        if (!$employee) {
            $this->dispatch('notify', type: 'error', message: 'Akun Anda tidak terhubung ke data karyawan.');
            return;
        }

        $mulai = \Carbon\Carbon::parse($this->pengajuanTanggalMulai);
        $selesai = \Carbon\Carbon::parse($this->pengajuanTanggalSelesai);
        $durasi = $mulai->diffInDays($selesai) + 1;

        $selectedPosition = $this->selectedPositionId
            ? Position::find($this->selectedPositionId)
            : $employee->mainPosition();

        $atasan = $this->getAtasan($employee, $selectedPosition);
        $atasan2 = $this->getAtasan2($employee, $atasan);

        $hasAtasan2 = $atasan2 !== null;

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'selected_position_id' => $selectedPosition?->id,
            'atasan_id' => $atasan?->id,
            'atasan2_id' => $atasan2?->id,
            'jenis' => $this->pengajuanJenis,
            'tanggal_mulai' => $this->pengajuanTanggalMulai,
            'tanggal_selesai' => $this->pengajuanTanggalSelesai,
            'durasi' => $durasi . ' hari',
            'keterangan' => $this->pengajuanKeterangan,
            'persetujuan_koor' => 'menunggu',
            'persetujuan_atasan2' => $hasAtasan2 ? 'menunggu' : 'disetujui',
            'persetujuan_hr' => 'menunggu',
        ]);

        $this->closePengajuanModal();
        $this->dispatch('notify', type: 'success', message: 'Pengajuan cuti/izin berhasil dikirim.');
    }

    public function setujui(int $id, string $level): void
    {
        $lr = LeaveRequest::with('employee')->findOrFail($id);
        $this->authorizeApproval($lr, $level);

        if ($level === 'persetujuan_atasan2' && $lr->persetujuan_koor !== 'disetujui') {
            $this->atasan2ErrorMessage = 'Atasan 1 belum menyetujui pengajuan ini. Atasan 2 hanya dapat menyetujui setelah Atasan 1 menyetujui.';
            $this->showAtasan2ErrorModal = true;
            return;
        }

        $user = auth()->user();
        if ($user->requiresPinApproval()) {
            if (!$user->hasPin()) {
                $this->showNoPinModal = true;
                return;
            }
            $this->pendingId = $id;
            $this->pendingLevel = $level;
            $this->pendingAction = 'setujui';
            $this->pin = '';
            $this->catatan = '';
            $this->showPinModal = true;
            return;
        }

        $lr->update([$level => 'disetujui']);
        $this->dispatch('notify', type: 'success', message: 'Pengajuan disetujui.');
    }

    public function tolak(int $id, string $level): void
    {
        $lr = LeaveRequest::with('employee')->findOrFail($id);
        $this->authorizeApproval($lr, $level);

        $user = auth()->user();
        if ($user->requiresPinApproval()) {
            if (!$user->hasPin()) {
                $this->showNoPinModal = true;
                return;
            }
            $this->pendingId = $id;
            $this->pendingLevel = $level;
            $this->pendingAction = 'tolak';
            $this->pin = '';
            $this->catatan = '';
            $this->showPinModal = true;
            return;
        }

        $lr->update([$level => 'ditolak']);
        $this->dispatch('notify', type: 'success', message: 'Pengajuan ditolak.');
    }

    public function cancelPinModal(): void
    {
        $this->showPinModal = false;
        $this->reset(['pin', 'catatan', 'pendingId', 'pendingLevel', 'pendingAction']);
    }

    public function submitPinApproval(): void
    {
        $this->validate([
            'pin' => ['required', 'string', 'size:6'],
        ]);

        $user = auth()->user();
        if (!$user->verifyPin($this->pin)) {
            $this->addError('pin', 'PIN Persetujuan yang Anda masukkan tidak sesuai.');
            return;
        }

        $lr = LeaveRequest::findOrFail($this->pendingId);
        $this->authorizeApproval($lr, $this->pendingLevel);

        if ($this->pendingLevel === 'persetujuan_atasan2' && $this->pendingAction === 'setujui' && $lr->persetujuan_koor !== 'disetujui') {
            $this->showPinModal = false;
            $this->reset(['pin', 'catatan', 'pendingId', 'pendingLevel', 'pendingAction']);
            $this->atasan2ErrorMessage = 'Atasan 1 belum menyetujui pengajuan ini. Atasan 2 hanya dapat menyetujui setelah Atasan 1 menyetujui.';
            $this->showAtasan2ErrorModal = true;
            return;
        }

        $status = $this->pendingAction === 'setujui' ? 'disetujui' : 'ditolak';
        $updateData = [$this->pendingLevel => $status];

        if ($this->catatan) {
            $updateData['catatan_persetujuan'] = $this->catatan;
        }

        $lr->update($updateData);

        $message = 'Pengajuan ' . ($this->pendingAction === 'setujui' ? 'disetujui' : 'ditolak') . '.';

        $this->showPinModal = false;
        $this->reset(['pin', 'catatan', 'pendingId', 'pendingLevel', 'pendingAction']);

        $this->dispatch('notify', type: 'success', message: $message);
    }

    private function authorizeApproval(LeaveRequest $lr, string $level): void
    {
        $user = auth()->user();

        if ($level === 'persetujuan_koor') {
            $userEmployee = $user->employee;
            if (!$userEmployee || $userEmployee->id !== $lr->atasan_id) {
                abort(403, 'Hanya atasan 1 yang dapat menyetujui pengajuan ini.');
            }
        } elseif ($level === 'persetujuan_atasan2') {
            $userEmployee = $user->employee;
            if (!$userEmployee || $userEmployee->id !== $lr->atasan2_id) {
                abort(403, 'Hanya atasan 2 yang dapat menyetujui pengajuan ini.');
            }
        } elseif ($level === 'persetujuan_hr') {
            if ($user->id !== 4 && !$this->isHr($user)) {
                abort(403, 'Hanya HR yang dapat menyetujui persetujuan HR.');
            }
        } else {
            abort(403);
        }
    }

    public function confirmDelete(int $id): void
    {
        $user = auth()->user();

        if ($user->isStaff()) {
            $employee = $user->employee;
            if (!$employee) {
                $this->dispatch('notify', type: 'error', message: 'Akun Anda tidak terhubung ke data karyawan.');
                return;
            }
            $lr = LeaveRequest::where('id', $id)->where('employee_id', $employee->id)->first();
            if (!$lr) {
                $this->dispatch('notify', type: 'error', message: 'Data tidak ditemukan.');
                return;
            }
            if ($lr->persetujuan_koor !== 'menunggu' && $lr->persetujuan_atasan2 !== 'menunggu' && $lr->persetujuan_hr !== 'menunggu') {
                $this->dispatch('notify', type: 'error', message: 'Hanya pengajuan yang masih menunggu yang dapat dihapus.');
                return;
            }
        } else {
            Gate::authorize('delete-data');
        }

        $this->deleteId = $id;
        $this->showDeleteConfirmModal = true;
    }

    public function executeDelete(): void
    {
        if (!$this->deleteId) return;

        $user = auth()->user();
        $lr = LeaveRequest::findOrFail($this->deleteId);

        if ($user->isStaff()) {
            $employee = $user->employee;
            if (!$employee || $lr->employee_id !== $employee->id) {
                abort(403);
            }
            if ($lr->persetujuan_koor !== 'menunggu' && $lr->persetujuan_atasan2 !== 'menunggu' && $lr->persetujuan_hr !== 'menunggu') {
                abort(403);
            }
        } else {
            Gate::authorize('delete-data');
        }

        $lr->delete();

        $this->showDeleteConfirmModal = false;
        $this->dispatch('notify', type: 'success', message: 'Pengajuan berhasil dihapus.');
        $this->deleteId = null;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteConfirmModal = false;
        $this->deleteId = null;
    }

    public function getSelectedPositionAtasan(): ?string
    {
        if (!$this->selectedPositionId) return null;
        $position = Position::find($this->selectedPositionId);
        if (!$position) return null;
        $atasan = $this->getAtasan(auth()->user()->employee, $position);
        return $atasan?->nama;
    }

    public function getSelectedPositionAtasan2(): ?string
    {
        if (!$this->selectedPositionId) return null;
        $position = Position::find($this->selectedPositionId);
        if (!$position) return null;
        $atasan1 = $this->getAtasan(auth()->user()->employee, $position);
        if (!$atasan1) return null;
        $atasan2 = $this->getAtasan2(auth()->user()->employee, $atasan1);
        return $atasan2?->nama;
    }

    public function render()
    {
        $user = auth()->user();
        $userEmployee = $user->employee;

        if ($user->isStaff()) {
            $employee = $userEmployee;

            if (!$employee) {
                $leaveRequests = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);

                return view('livewire.cuti-izin-table', [
                    'karyawanView' => true,
                    'employee' => null,
                    'totalPengajuanSaya' => 0,
                    'totalCutiSaya' => 0,
                    'totalIzinSaya' => 0,
                    'menungguCuti' => 0,
                    'menungguIzin' => 0,
                    'sisaCuti' => 0,
                    'jatahCuti' => 12,
                    'leaveRequests' => $leaveRequests,
                ]);
            }

            $totalPengajuanSaya = LeaveRequest::where('employee_id', $employee->id)->count();
            $totalCutiSaya = LeaveRequest::where('employee_id', $employee->id)->where('jenis', 'cuti_tahunan')->count();
            $totalIzinSaya = LeaveRequest::where('employee_id', $employee->id)->where('jenis', 'izin')->count();
            $menungguCuti = LeaveRequest::where('employee_id', $employee->id)
                ->where('jenis', 'cuti_tahunan')
                ->where(function ($q) {
                    $q->where('persetujuan_koor', 'menunggu')
                      ->orWhere('persetujuan_atasan2', 'menunggu')
                      ->orWhere('persetujuan_hr', 'menunggu');
                })->count();
            $menungguIzin = LeaveRequest::where('employee_id', $employee->id)
                ->where('jenis', 'izin')
                ->where(function ($q) {
                    $q->where('persetujuan_koor', 'menunggu')
                      ->orWhere('persetujuan_atasan2', 'menunggu')
                      ->orWhere('persetujuan_hr', 'menunggu');
                })->count();

            $jatahCuti = 12;
            $usedCuti = LeaveRequest::where('employee_id', $employee->id)
                ->where('jenis', 'cuti_tahunan')
                ->whereYear('tanggal_mulai', now()->year)
                ->where('persetujuan_koor', 'disetujui')
                ->where('persetujuan_atasan2', 'disetujui')
                ->where('persetujuan_hr', 'disetujui')
                ->get()
                ->sum(fn($lr) => (int) filter_var($lr->durasi, FILTER_SANITIZE_NUMBER_INT));
            $sisaCuti = max(0, $jatahCuti - $usedCuti);

            $leaveRequests = LeaveRequest::with('employee', 'atasan', 'atasan2', 'selectedPosition')
                ->where('employee_id', $employee->id)
                ->when($this->filterJenis, function ($query) {
                    $query->where('jenis', $this->filterJenis);
                })
                ->when($this->filterStatus, function ($query) {
                    $query->where(function ($q) {
                        $q->where('persetujuan_koor', $this->filterStatus)
                          ->orWhere('persetujuan_atasan2', $this->filterStatus)
                          ->orWhere('persetujuan_hr', $this->filterStatus);
                    });
                })
                ->latest()
                ->paginate(10);

            $userPositions = $employee->positions;

            return view('livewire.cuti-izin-table', compact(
                'employee', 'userPositions', 'totalPengajuanSaya', 'totalCutiSaya', 'totalIzinSaya', 'menungguCuti', 'menungguIzin', 'leaveRequests', 'sisaCuti', 'jatahCuti'
            ))->with('karyawanView', true);
        }

        $isHr = $userEmployee && $userEmployee->positions()->whereIn('nama', [
            'Human Resource Generalist', 'Admin HR', 'Admin GA', 'Office Boy'
        ])->exists();
        $lihatSemua = $user->id === 4 || $isHr || ($user->canViewAll() && !$user->isKoordinator());

        $baseQuery = LeaveRequest::query();

        if ($userEmployee && !$lihatSemua) {
            $baseQuery->where(function ($q) use ($userEmployee) {
                $q->where('atasan_id', $userEmployee->id)
                  ->orWhere('atasan2_id', $userEmployee->id);
            });
        }

        $totalPengajuan = (clone $baseQuery)->count();
        $totalCuti = (clone $baseQuery)->where('jenis', 'cuti_tahunan')->count();
        $totalIzin = (clone $baseQuery)->where('jenis', 'izin')->count();
        $menunggu = (clone $baseQuery)->where(function ($q) {
            $q->where('persetujuan_koor', 'menunggu')
              ->orWhere('persetujuan_atasan2', 'menunggu')
              ->orWhere('persetujuan_hr', 'menunggu');
        })->count();

        $leaveRequests = (clone $baseQuery)
            ->with('employee', 'atasan', 'atasan2', 'selectedPosition')
            ->when($this->search, function ($query) {
                $query->whereHas('employee', function ($q) {
                    $q->where('nama', 'like', "%{$this->search}%")
                      ->orWhere('nik', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterJenis, function ($query) {
                $query->where('jenis', $this->filterJenis);
            })
            ->when($this->filterStatus, function ($query) {
                $query->where(function ($q) {
                    $q->where('persetujuan_koor', $this->filterStatus)
                      ->orWhere('persetujuan_atasan2', $this->filterStatus)
                      ->orWhere('persetujuan_hr', $this->filterStatus);
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.cuti-izin-table', compact(
            'leaveRequests', 'totalPengajuan', 'totalCuti', 'totalIzin', 'menunggu', 'userEmployee', 'isHr', 'user'
        ))->with('karyawanView', false);
    }

    private function getAtasan(Employee $employee, ?Position $position = null): ?Employee
    {
        $pos = $position ?? $employee->mainPosition();
        if (!$pos || !$pos->parent_id) return null;

        $current = $pos->parent;
        while ($current) {
            $atasan = $current->employees()->first();
            if ($atasan) return $atasan;
            $current = $current->parent;
        }

        return null;
    }

    private function getAtasan2(Employee $employee, ?Employee $atasan1 = null): ?Employee
    {
        $atasan2Field = trim($employee->atasan2 ?? '');
        if ($atasan2Field === '' || $atasan2Field === '-') {
            return null;
        }

        $atasan2 = Employee::where('nama', $atasan2Field)->first();
        if ($atasan2) return $atasan2;

        $atasan1 = $atasan1 ?? $this->getAtasan($employee);
        if (!$atasan1) return null;

        $position = $atasan1->mainPosition();
        if (!$position || !$position->parent_id) return null;

        $current = $position->parent;
        while ($current) {
            $atasan2 = $current->employees()->first();
            if ($atasan2) return $atasan2;
            $current = $current->parent;
        }

        return null;
    }

    private function isHr($user): bool
    {
        $emp = $user->employee;
        if (!$emp) return false;
        return $emp->positions()->whereIn('nama', [
            'Human Resource Generalist', 'Admin HR', 'Admin GA', 'Office Boy'
        ])->exists();
    }
}
