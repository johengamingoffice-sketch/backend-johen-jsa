<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class AbsensiTable extends Component
{
    use WithPagination;

    public string $date = '';
    public string $search = '';
    public string $tab = 'saya';

    public bool $showAbsenModal = false;
    public string $absenStatus = 'hadir';

    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openAbsenModal(): void
    {
        $this->showAbsenModal = true;
        $this->absenStatus = 'hadir';
    }

    public function closeAbsenModal(): void
    {
        $this->showAbsenModal = false;
        $this->resetErrorBag();
    }

    public function submitAbsen(): void
    {
        $this->validate([
            'absenStatus' => ['required', 'in:hadir,izin,sakit'],
        ]);

        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            $this->dispatch('notify', type: 'error', message: 'Akun Anda tidak terhubung ke data karyawan.');
            return;
        }

        $cek = Attendance::where('employee_id', $employee->id)->where('date', today())->first();
        if ($cek) {
            $this->dispatch('notify', type: 'error', message: 'Anda sudah melakukan absensi hari ini.');
            $this->closeAbsenModal();
            return;
        }

        Attendance::create([
            'employee_id' => $employee->id,
            'date' => today(),
            'time_in' => now()->format('H:i:s'),
            'status' => $this->absenStatus === 'hadir' ? 'hadir' : $this->absenStatus,
        ]);

        $this->closeAbsenModal();
        $this->dispatch('notify', type: 'success', message: 'Absensi berhasil dicatat.');
    }

    public function render()
    {
        $user = auth()->user();
        $today = $this->date;

        if ($user->isStaff()) {
            $employee = $user->employee;

            if (!$employee) {
                $riwayat = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);

                return view('livewire.absensi-table', [
                    'karyawanView' => true,
                    'employee' => null,
                    'totalAbsensi' => 0,
                    'tepatWaktu' => 0,
                    'terlambat' => 0,
                    'totalHadir' => 0,
                    'attendances' => collect(),
                    'riwayat' => $riwayat,
                    'attendanceHariIni' => null,
                    'today' => $today,
                ]);
            }

            $riwayat = Attendance::where('employee_id', $employee->id)
                ->orderBy('date', 'desc')
                ->paginate(10);

            $semuaAbsensi = Attendance::where('employee_id', $employee->id)->get();
            $totalAbsensi = $semuaAbsensi->count();
            $tepatWaktu = $semuaAbsensi->filter(fn($a) =>
                $a->status === 'hadir' && (!$a->time_in || $a->time_in <= '09:00:00')
            )->count();
            $terlambat = $semuaAbsensi->filter(fn($a) =>
                $a->status === 'hadir' && $a->time_in && $a->time_in > '09:00:00'
            )->count();
            $totalHadir = $tepatWaktu + $terlambat;
            $attendanceHariIni = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', today())->first();

            return view('livewire.absensi-table', compact(
                'employee', 'totalAbsensi', 'tepatWaktu', 'terlambat', 'totalHadir',
                'riwayat', 'attendanceHariIni', 'today'
            ))->with('karyawanView', true);
        }

        if ($user->isKoordinator() && $this->tab === 'saya') {
            $employee = $user->employee;

            if (!$employee) {
                $riwayat = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);

                return view('livewire.absensi-table', [
                    'karyawanView' => false,
                    'koordinatorView' => true,
                    'employee' => null,
                    'totalAbsensi' => 0,
                    'tepatWaktu' => 0,
                    'terlambat' => 0,
                    'totalHadir' => 0,
                    'attendances' => collect(),
                    'riwayat' => $riwayat,
                    'attendanceHariIni' => null,
                    'today' => $today,
                ]);
            }

            $riwayat = Attendance::where('employee_id', $employee->id)
                ->orderBy('date', 'desc')
                ->paginate(10);

            $semuaAbsensi = Attendance::where('employee_id', $employee->id)->get();
            $totalAbsensi = $semuaAbsensi->count();
            $tepatWaktu = $semuaAbsensi->filter(fn($a) =>
                $a->status === 'hadir' && (!$a->time_in || $a->time_in <= '09:00:00')
            )->count();
            $terlambat = $semuaAbsensi->filter(fn($a) =>
                $a->status === 'hadir' && $a->time_in && $a->time_in > '09:00:00'
            )->count();
            $totalHadir = $tepatWaktu + $terlambat;
            $attendanceHariIni = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', today())->first();

            return view('livewire.absensi-table', compact(
                'employee', 'totalAbsensi', 'tepatWaktu', 'terlambat', 'totalHadir',
                'riwayat', 'attendanceHariIni', 'today'
            ))->with('karyawanView', false)->with('koordinatorView', true);
        }

        $attendances = Attendance::with('employee.division')
            ->whereDate('date', $today)
            ->get()
            ->keyBy('employee_id');

        $employeeQuery = Employee::with('division')->where('status', 'aktif');

        if ($user->isKoordinator() && $this->tab === 'tim') {
            $koordinatorEmployee = $user->employee;
            if ($koordinatorEmployee && $koordinatorEmployee->division_id) {
                $employeeQuery->where('division_id', $koordinatorEmployee->division_id)
                    ->where('id', '!=', $koordinatorEmployee->id);
            }
        }

        $totalKaryawan = (clone $employeeQuery)->count();

        $teamIds = (clone $employeeQuery)->pluck('id')->toArray();
        $teamAttendances = $attendances->only($teamIds);

        $hadir = $teamAttendances->filter(fn($a) =>
            $a->status === 'hadir' && (!$a->time_in || $a->time_in <= '09:00:00')
        )->count();

        $terlambat = $teamAttendances->filter(fn($a) =>
            $a->status === 'hadir' && $a->time_in && $a->time_in > '09:00:00'
        )->count();

        $totalHadir = $hadir + $terlambat;

        $employees = $employeeQuery
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nik', 'like', "%{$this->search}%")
                      ->orWhere('nama', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('nama')
            ->paginate(10);

        return view('livewire.absensi-table', compact(
            'attendances', 'totalKaryawan', 'hadir', 'terlambat', 'totalHadir', 'employees', 'today'
        ))->with('karyawanView', false);
    }
}
