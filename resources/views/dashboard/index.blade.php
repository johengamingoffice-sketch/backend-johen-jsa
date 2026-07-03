@push('topbar-left')
    <div>
        <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Dashboard</h1>
        <p class="text-xs text-gray-400 mt-0.5">Ringkasan seluruh data dan aktivitas</p>
    </div>
@endpush

<x-app-layout title="Dashboard">

@if($karyawanView ?? false)

    @if($employee && $karyawanData)
    {{-- 1. Welcome Header --}}
    <div class="card p-5 mb-6">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            </div>
            <div class="min-w-0">
                <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">Selamat Datang, <span class="text-primary-600 dark:text-primary-400">{{ $employee->nama }}</span></h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $employee->position ?? '-' }} • {{ $employee->division?->nama ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- 2. Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="badge-info text-[10px]">Bulan Ini</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $karyawanData['total_hadir_bulan_ini'] }} <span class="text-sm font-medium text-gray-400">Hari</span></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kehadiran Bulan Ini</p>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <span class="badge-success text-[10px]">Tahunan</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $karyawanData['sisa_cuti'] }} <span class="text-sm font-medium text-gray-400">/ {{ $karyawanData['jatah_cuti'] }} Hari</span></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Sisa Cuti Tahunan</p>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="badge-warning text-[10px]">Menunggu</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $karyawanData['pending_count'] }} <span class="text-sm font-medium text-gray-400">Pengajuan</span></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pengajuan Menunggu</p>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                </div>
                <span class="badge-danger text-[10px]">Bulan Ini</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $karyawanData['total_terlambat_bulan_ini'] }} <span class="text-sm font-medium text-gray-400">Kali</span></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Keterlambatan Bulan Ini</p>
        </div>
    </div>

    {{-- 3. Quick Action --}}
    <div class="card p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-4">Aksi Cepat</h3>
        <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 1rem;">
            <a href="{{ route('hris.absensi') }}" class="flex flex-col items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-200 hover:scale-110">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-xs font-medium text-center leading-tight">Absen Masuk</span>
            </a>
            <a href="{{ route('hris.absensi') }}" class="flex flex-col items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-200 hover:scale-110">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                <span class="text-xs font-medium text-center leading-tight">Absen Pulang</span>
            </a>
            <a href="{{ route('hris.cuti-izin') }}" class="flex flex-col items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-200 hover:scale-110">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                <span class="text-xs font-medium text-center leading-tight">Ajukan Cuti</span>
            </a>
            <a href="{{ route('hris.cuti-izin') }}" class="flex flex-col items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-200 hover:scale-110">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-xs font-medium text-center leading-tight">Ajukan Izin</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-200 hover:scale-110">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
                <span class="text-xs font-medium text-center leading-tight">Ajukan Lembur</span>
            </a>
            <a href="{{ route('meeting.jadwal') }}" class="flex flex-col items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-200 hover:scale-110">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                <span class="text-xs font-medium text-center leading-tight">Lihat Jadwal</span>
            </a>
        </div>
    </div>

    {{-- 4. Absensi Hari Ini ~ 5. Jadwal Kerja --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- 3. Jam Kedatangan & Status Kehadiran --}}
        <div class="lg:col-span-2 card p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Jam Kedatangan & Status Kehadiran</h3>
                <span class="text-xs text-gray-400">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</span>
            </div>
            @if($karyawanData['attendance_today'])
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-5 flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Jam Kedatangan</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $karyawanData['attendance_today']['time_in'] }}</p>
                        </div>
                    </div>
                    <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-5 flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Status Kehadiran</p>
                            @if($karyawanData['attendance_today']['status'] === 'tepat waktu' || $karyawanData['attendance_today']['status'] === 'hadir')
                                <span class="badge-success text-sm mt-1 inline-block">Hadir</span>
                            @elseif($karyawanData['attendance_today']['status'] === 'terlambat')
                                <span class="badge-warning text-sm mt-1 inline-block">Terlambat</span>
                            @else
                                <span class="badge-secondary text-sm mt-1 inline-block">{{ ucfirst($karyawanData['attendance_today']['status']) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Belum Ada Absensi Hari Ini</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Silakan lakukan absensi masuk</p>
                        </div>
                    </div>
                    <a href="{{ route('hris.absensi') }}" class="btn-primary text-xs py-2 px-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                        Absen Sekarang
                    </a>
                </div>
            @endif
        </div>

        {{-- Jadwal Kerja Hari Ini --}}
        <div class="card p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Jadwal Kerja</h3>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 mb-1">Shift</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">Pagi</p>
                </div>
                <div class="flex items-center gap-3 py-3 border-t border-gray-100 dark:border-gray-700">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Jam Kerja</p>
                        <p class="text-xs font-semibold text-gray-900 dark:text-gray-100">08:00 - 17:00</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 py-3 border-t border-gray-100 dark:border-gray-700">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Lokasi Kerja</p>
                        <p class="text-xs font-semibold text-gray-900 dark:text-gray-100">{{ $employee->lokasi_kerja ?? 'Kantor Pusat' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 py-3 border-t border-gray-100 dark:border-gray-700">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Supervisor</p>
                        <p class="text-xs font-semibold text-gray-900 dark:text-gray-100">{{ $employee->atasan ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 5. Pengumuman Terbaru --}}
    <div class="card p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Pengumuman Terbaru</h3>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38a.467.467 0 01-.502-.011 5.095 5.095 0 01-1.357-3.637m3.394-5.026a9.44 9.44 0 000 4.52M3.554 9.48l-.397.73a.72.72 0 000 .59l.397.73m7.446-5.71v-.75c0-.663.284-1.275.73-1.74 0 0 1.813-1.87 3.042-2.27.291-.094.603.06.603.366v4.133m6.659 8.677l.397-.73a.72.72 0 000-.59l-.397-.73M18.304 8.88l1.26-1.08c.33-.283.363-.795.063-1.137m-8.865 3.827a6.03 6.03 0 00-.706.74m.706-.74c.62-.24 1.29-.37 1.99-.37h1.5a4.5 4.5 0 010 9h-.75c-.705 0-1.403.03-2.09.09"/></svg>
        </div>
        @if(count($karyawanData['announcements'] ?? []) > 0)
            <div class="space-y-3">
                @foreach($karyawanData['announcements'] as $ann)
                    <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:border-primary-200 dark:hover:border-primary-800 transition-all cursor-pointer">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $ann['title'] }}</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">{{ $ann['summary'] ?? '-' }}</p>
                            </div>
                            <span class="shrink-0 text-[10px] text-gray-400">{{ $ann['date'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 text-center">
                <a href="#" class="text-xs font-semibold text-primary-600 hover:text-primary-700 hover:underline">Lihat Semua Pengumuman</a>
            </div>
        @else
            <div class="py-8 text-center">
                <svg class="w-8 h-8 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38a.467.467 0 01-.502-.011 5.095 5.095 0 01-1.357-3.637m3.394-5.026a9.44 9.44 0 000 4.52M3.554 9.48l-.397.73a.72.72 0 000 .59l.397.73m7.446-5.71v-.75c0-.663.284-1.275.73-1.74 0 0 1.813-1.87 3.042-2.27.291-.094.603.06.603.366v4.133m6.659 8.677l.397-.73a.72.72 0 000-.59l-.397-.73M18.304 8.88l1.26-1.08c.33-.283.363-.795.063-1.137m-8.865 3.827a6.03 6.03 0 00-.706.74m.706-.74c.62-.24 1.29-.37 1.99-.37h1.5a4.5 4.5 0 010 9h-.75c-.705 0-1.403.03-2.09.09"/></svg>
                <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada pengumuman</p>
            </div>
        @endif
    </div>

    {{-- Payroll Terakhir --}}
    @if($karyawanData['latest_payroll'])
    <div class="card p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Slip Gaji Terakhir</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $karyawanData['latest_payroll']['periode'] }}</p>
            </div>
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-green-500 text-white shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3"/></svg>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-4 text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400">Gaji Pokok</p>
                <p class="text-lg font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($karyawanData['latest_payroll']['gaji_pokok'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-xl bg-emerald-50 dark:bg-emerald-900/10 p-4 text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400">Take Home Pay</p>
                <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($karyawanData['latest_payroll']['take_home_pay'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-xl bg-blue-50 dark:bg-blue-900/10 p-4 text-center flex items-center justify-center">
                <a href="{{ route('history.index') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700 hover:underline">
                    Lihat Riwayat
                </a>
            </div>
        </div>
    </div>
    @endif

    @else
    {{-- No employee linked --}}
    <div class="card p-12 text-center">
        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 dark:bg-gray-800">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Akun Belum Terhubung</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Akun Anda belum terhubung ke data karyawan. Silakan hubungi admin.</p>
    </div>
    @endif

@else
    {{-- Admin / Direksi Dashboard --}}

    {{-- Ringkasan Menu --}}
    <div x-data="{ openDivisiModal: false, openMeetingModal: false }">
    <div class="grid grid-cols-1 md:grid-cols-2 {{ in_array(auth()->user()->role, ['super_admin', 'gm_ceo']) ? 'xl:grid-cols-4' : 'xl:grid-cols-3' }} gap-5">
        <div @click="openDivisiModal = true" class="card p-5 hover:shadow-md hover:border-blue-100 dark:hover:border-blue-900 transition-all group cursor-pointer">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 text-white shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">Data SDM</p>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Karyawan & Divisi</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-sm">
                <div>
                    <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_employees'] }}</span>
                    <span class="text-gray-400 dark:text-gray-500 ml-1">Karyawan</span>
                </div>
                <div>
                    <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_divisions'] }}</span>
                    <span class="text-gray-400 dark:text-gray-500 ml-1">Divisi</span>
                </div>
            </div>
        </div>

        @if(in_array(auth()->user()->role, ['super_admin', 'gm_ceo']))
        <a href="{{ route('history.index') }}" class="card p-5 hover:shadow-md hover:border-blue-100 dark:hover:border-blue-900 transition-all group">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-green-500 text-white shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">Keuangan</p>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Payroll, Bonus & Insentif</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-sm">
                <div>
                    <span class="text-lg font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($stats['total_payroll'], 0, ',', '.') }}</span>
                    <span class="text-gray-400 dark:text-gray-500 ml-1 block text-[11px] -mt-0.5">Total Payroll</span>
                </div>
            </div>
        </a>
        @endif

        <div @click="openMeetingModal = true" class="card p-5 hover:shadow-md hover:border-blue-100 dark:hover:border-blue-900 transition-all group cursor-pointer">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-teal-500 to-cyan-500 text-white shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">Total Meeting</p>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Meeting bulan ini</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-sm">
                <div>
                    <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $meetingStats['total_meetings'] }}</span>
                    <span class="text-gray-400 dark:text-gray-500 ml-1">Meeting</span>
                </div>
            </div>
        </div>

        <div class="card p-5 opacity-70">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-violet-500 text-white shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">Data Asset</p>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Kendaraan, Digital, SIM Card, dll</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-sm">
                <div>
                    <span class="text-lg font-bold text-gray-400 dark:text-gray-500">—</span>
                    <span class="text-gray-400 dark:text-gray-500 ml-1">Belum tersedia</span>
                </div>
            </div>
        </div>
    </div>

    {{-- DIVISI MODAL --}}
    <div x-show="openDivisiModal" x-cloak
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-start justify-center p-4 pt-10 bg-gray-900/60 backdrop-blur-sm overflow-y-auto"
         @click="openDivisiModal = false">
        <div x-show="openDivisiModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.stop class="relative w-full max-w-2xl rounded-2xl bg-white dark:bg-gray-800 p-8 shadow-2xl my-10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Pilih Divisi</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Klik divisi untuk melihat daftar karyawan</p>
                </div>
                <button @click="openDivisiModal = false" class="rounded-xl p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('hris.employees.index') }}"
                   class="flex items-center justify-between p-4 rounded-xl border-2 border-primary-100 dark:border-primary-900/50 bg-primary-50/50 dark:bg-primary-900/10 hover:border-primary-300 dark:hover:border-primary-700 transition-all group">
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100">Semua Karyawan</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Seluruh divisi</p>
                    </div>
                    <span class="text-lg font-bold text-primary-600 dark:text-primary-400">{{ $stats['total_employees'] }}</span>
                </a>

                @foreach($divisionStats as $ds)
                <a href="{{ route('hris.employees.index', ['division' => $ds['id']]) }}"
                   class="flex items-center justify-between p-4 rounded-xl border-2 border-gray-100 dark:border-gray-700 hover:border-primary-200 dark:hover:border-primary-800 hover:bg-primary-50/30 dark:hover:bg-primary-900/5 transition-all group">
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $ds['nama'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $ds['total'] }} karyawan</p>
                    </div>
                    <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $ds['total'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MEETING MODAL --}}
    <div x-show="openMeetingModal" x-cloak
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-start justify-center p-4 pt-10 bg-gray-900/60 backdrop-blur-sm overflow-y-auto"
         @click="openMeetingModal = false">
        <div x-show="openMeetingModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.stop class="relative w-full max-w-2xl rounded-2xl bg-white dark:bg-gray-800 p-8 shadow-2xl my-10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Meeting per Divisi</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total meeting bulan ini</p>
                </div>
                <button @click="openMeetingModal = false" class="rounded-xl p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-center justify-between p-4 rounded-xl border-2 border-teal-100 dark:border-teal-900/50 bg-teal-50/50 dark:bg-teal-900/10 transition-all group">
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100">Semua Divisi</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total meeting</p>
                    </div>
                    <span class="text-lg font-bold text-teal-600 dark:text-teal-400">{{ $meetingStats['total_meetings'] }}</span>
                </div>

                @foreach($meetingStats['per_division'] as $md)
                <div class="flex items-center justify-between p-4 rounded-xl border-2 border-gray-100 dark:border-gray-700 transition-all group">
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $md['nama'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">0 meeting</p>
                    </div>
                    <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $md['total'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    </div>

    {{-- 2x2 Grid --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Kontrak Akan Berakhir --}}
        <div class="card p-5 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Kontrak Akan Berakhir</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Karyawan dengan kontrak segera habis</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-rose-500 to-red-500 text-white shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            @if($expiringContractCount > 0)
                <div class="flex-1 space-y-2.5 overflow-y-auto max-h-[200px]">
                    @foreach($expiringContracts as $ec)
                        @php $isUrgent = $ec['days_remaining'] < 3; @endphp
                        <div class="flex items-center justify-between p-2.5 rounded-lg {{ $isUrgent ? 'bg-rose-50 dark:bg-rose-900/10 border-rose-100 dark:border-rose-900/30' : 'bg-amber-50 dark:bg-amber-900/10 border-amber-100 dark:border-amber-900/30' }}">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $ec['employee'] }}</p>
                                <p class="text-[11px] {{ $isUrgent ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400' }}">{{ $ec['posisi'] }} • Berakhir {{ $ec['tanggal_berakhir'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="pt-3 mt-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <span class="text-xs font-semibold text-rose-600 dark:text-rose-400">{{ $expiringContractCount }} kontrak akan berakhir</span>
                    <a href="{{ route('hris.kontrak-kerja') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700 hover:underline">
                        Lihat Selengkapnya &rarr;
                    </a>
                </div>
            @else
                <div class="flex-1 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-8 h-8 mx-auto text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Semua kontrak masih berlaku</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Pengajuan Reimbursement --}}
        <div class="card p-5 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Pengajuan Reimbursement</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Pengajuan dana yang perlu diproses</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-500 to-teal-500 text-white shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                </div>
            </div>
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-8 h-8 mx-auto text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-gray-400 dark:text-gray-500">Tidak ada pengajuan reimbursement</p>
                </div>
            </div>
        </div>

        {{-- Pengajuan Cuti & Izin --}}
        <div class="card p-5 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Pengajuan Cuti & Izin</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Cuti & Izin perlu persetujuan</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                </div>
            </div>
            @if($pendingLeaveCount > 0)
                <div class="flex-1 space-y-2.5 overflow-y-auto max-h-[200px]">
                    @foreach($pendingLeaveRequests as $pl)
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/30">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $pl['employee'] }}</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ $pl['jenis'] }} • {{ $pl['tanggal'] }}</p>
                            </div>
                            <a href="{{ route('hris.cuti-izin') }}" class="shrink-0 text-[10px] font-semibold text-amber-600 hover:text-amber-700 hover:underline ml-2">
                                Proses
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="pt-3 mt-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <span class="text-xs font-semibold text-amber-600 dark:text-amber-400">{{ $pendingLeaveCount }} pengajuan menunggu</span>
                    <a href="{{ route('hris.cuti-izin') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700 hover:underline">
                        Lihat Selengkapnya &rarr;
                    </a>
                </div>
            @else
                <div class="flex-1 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-8 h-8 mx-auto text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Semua sudah diproses</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Pembayaran Mendatang --}}
        <div class="card p-5 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Pembayaran Mendatang</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Tagihan & langganan yang perlu diperhatikan</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                </div>
            </div>

            @php
                $internetDays = 5;
                $isUrgent = $internetDays <= 3;
                $internetWarnClass = $isUrgent ? 'bg-rose-50 dark:bg-rose-900/10 border-rose-100 dark:border-rose-900/30' : 'bg-amber-50 dark:bg-amber-900/10 border-amber-100 dark:border-amber-900/30';
                $internetTextClass = $isUrgent ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400';
                $internetBgIcon = $isUrgent ? 'bg-rose-100 dark:bg-rose-900/20' : 'bg-amber-100 dark:bg-amber-900/20';
                $internetIconClass = $isUrgent ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400';
            @endphp
            <div class="flex-1 space-y-2.5">
                <div class="flex items-center justify-between p-3 rounded-lg {{ $internetWarnClass }}">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="shrink-0 w-8 h-8 flex items-center justify-center rounded-lg {{ $internetBgIcon }}">
                            <svg class="w-4 h-4 {{ $internetIconClass }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-gray-900 dark:text-gray-100">Internet</p>
                            <p class="text-[11px] {{ $internetTextClass }}">Mendekati masa tenggang — {{ $internetDays }} hari lagi</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-3 mt-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <span class="text-xs font-semibold {{ $isUrgent ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400' }}">1 pembayaran perlu diperhatikan</span>
                <span class="text-xs font-semibold text-gray-400 cursor-default">Lihat Selengkapnya &rarr;</span>
            </div>
        </div>
    </div>

@endif

</x-app-layout>
