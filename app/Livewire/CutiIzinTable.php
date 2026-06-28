<?php

namespace App\Livewire;

use App\Models\LeaveRequest;
use App\Models\Employee;
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
    public string $pengajuanJenis = 'cuti_tahunan';
    public string $pengajuanTanggalMulai = '';
    public string $pengajuanTanggalSelesai = '';
    public string $pengajuanKeterangan = '';

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
        $this->resetErrorBag();
    }

    public function closePengajuanModal(): void
    {
        $this->showPengajuanModal = false;
        $this->resetErrorBag();
    }

    public function submitPengajuan(): void
    {
        $this->validate([
            'pengajuanJenis' => ['required', 'in:cuti_tahunan,izin'],
            'pengajuanTanggalMulai' => ['required', 'date'],
            'pengajuanTanggalSelesai' => ['required', 'date', 'after_or_equal:pengajuanTanggalMulai'],
            'pengajuanKeterangan' => ['required', 'string', 'max:1000'],
        ]);

        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            $this->dispatch('notify', type: 'error', message: 'Akun Anda tidak terhubung ke data karyawan.');
            return;
        }

        $mulai = \Carbon\Carbon::parse($this->pengajuanTanggalMulai);
        $selesai = \Carbon\Carbon::parse($this->pengajuanTanggalSelesai);
        $durasi = $mulai->diffInDays($selesai) + 1;

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'jenis' => $this->pengajuanJenis,
            'tanggal_mulai' => $this->pengajuanTanggalMulai,
            'tanggal_selesai' => $this->pengajuanTanggalSelesai,
            'durasi' => $durasi . ' hari',
            'keterangan' => $this->pengajuanKeterangan,
            'persetujuan_koor' => 'menunggu',
            'persetujuan_hr' => 'menunggu',
        ]);

        $this->closePengajuanModal();
        $this->dispatch('notify', type: 'success', message: 'Pengajuan berhasil dikirim.');
    }

    public function setujui(int $id, string $level): void
    {
        Gate::authorize('update-data');
        $lr = LeaveRequest::findOrFail($id);
        $lr->update([$level => 'disetujui']);
        $this->dispatch('notify', type: 'success', message: 'Pengajuan disetujui.');
    }

    public function tolak(int $id, string $level): void
    {
        Gate::authorize('update-data');
        $lr = LeaveRequest::findOrFail($id);
        $lr->update([$level => 'ditolak']);
        $this->dispatch('notify', type: 'success', message: 'Pengajuan ditolak.');
    }

    public function hapus(int $id): void
    {
        $user = auth()->user();
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

        if ($lr->persetujuan_koor !== 'menunggu' && $lr->persetujuan_hr !== 'menunggu') {
            $this->dispatch('notify', type: 'error', message: 'Hanya pengajuan yang masih menunggu yang dapat dihapus.');
            return;
        }

        $lr->delete();
        $this->dispatch('notify', type: 'success', message: 'Pengajuan berhasil dihapus.');
    }

    public function hapusAdmin(int $id): void
    {
        Gate::authorize('delete-data');
        LeaveRequest::findOrFail($id)->delete();
        $this->dispatch('notify', type: 'success', message: 'Pengajuan berhasil dihapus.');
    }

    public function render()
    {
        $user = auth()->user();

        if ($user->isKaryawan()) {
            $employee = $user->employee;

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
                      ->orWhere('persetujuan_hr', 'menunggu');
                })->count();
            $menungguIzin = LeaveRequest::where('employee_id', $employee->id)
                ->where('jenis', 'izin')
                ->where(function ($q) {
                    $q->where('persetujuan_koor', 'menunggu')
                      ->orWhere('persetujuan_hr', 'menunggu');
                })->count();

            $jatahCuti = 12;
            $usedCuti = LeaveRequest::where('employee_id', $employee->id)
                ->where('jenis', 'cuti_tahunan')
                ->whereYear('tanggal_mulai', now()->year)
                ->where('persetujuan_koor', 'disetujui')
                ->where('persetujuan_hr', 'disetujui')
                ->get()
                ->sum(fn($lr) => (int) filter_var($lr->durasi, FILTER_SANITIZE_NUMBER_INT));
            $sisaCuti = max(0, $jatahCuti - $usedCuti);

            $leaveRequests = LeaveRequest::with('employee')
                ->where('employee_id', $employee->id)
                ->when($this->filterJenis, function ($query) {
                    $query->where('jenis', $this->filterJenis);
                })
                ->latest()
                ->paginate(10);

            return view('livewire.cuti-izin-table', compact(
                'employee', 'totalPengajuanSaya', 'totalCutiSaya', 'totalIzinSaya', 'menungguCuti', 'menungguIzin', 'leaveRequests', 'sisaCuti', 'jatahCuti'
            ))->with('karyawanView', true);
        }

        $totalPengajuan = LeaveRequest::count();
        $totalCuti = LeaveRequest::where('jenis', 'cuti_tahunan')->count();
        $totalIzin = LeaveRequest::where('jenis', 'izin')->count();
        $menunggu = LeaveRequest::where('persetujuan_koor', 'menunggu')->orWhere('persetujuan_hr', 'menunggu')->count();

        $leaveRequests = LeaveRequest::with('employee')
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
                      ->orWhere('persetujuan_hr', $this->filterStatus);
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.cuti-izin-table', compact(
            'leaveRequests', 'totalPengajuan', 'totalCuti', 'totalIzin', 'menunggu'
        ))->with('karyawanView', false);
    }
}
