@push('topbar-left')
    <div>
        <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Jobdesk Saya</h1>
        <p class="text-xs text-gray-400 mt-0.5">Informasi jabatan dan tanggung jawab</p>
    </div>
@endpush

<x-app-layout title="Jobdesk Saya">

@if($employee)
    <div class="card p-5">
        <div class="flex items-center gap-4 mb-6">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $employee->nama }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $employee->position ?? '-' }} • {{ $employee->division?->nama ?? '-' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-5">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Informasi Jabatan</h3>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Posisi</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $employee->position ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Divisi</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $employee->division?->nama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Atasan</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $employee->atasan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Lokasi Kerja</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $employee->lokasi_kerja ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-5">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Riwayat Jabatan</h3>
                </div>
                @if($employee->positionHistories->count() > 0)
                    <div class="space-y-2">
                        @foreach($employee->positionHistories->sortByDesc('tanggal_mulai') as $history)
                            <div class="p-2.5 rounded-lg bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700">
                                <p class="text-xs font-semibold text-gray-900 dark:text-gray-100">{{ $history->position }}</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ $history->tanggal_mulai?->format('d M Y') ?? '-' }} @if($history->tanggal_selesai) — {{ $history->tanggal_selesai->format('d M Y') }} @endif</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-6">Belum ada riwayat jabatan</p>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="card p-12 text-center">
        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 dark:bg-gray-800">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Data Tidak Tersedia</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Akun Anda belum terhubung ke data karyawan.</p>
    </div>
@endif

</x-app-layout>
