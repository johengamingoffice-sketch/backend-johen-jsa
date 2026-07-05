<x-app-layout title="Pembayaran Internet">
    @push('topbar-left')
        <div>
            <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Pembayaran Internet</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Kelola tagihan WiFi dan pengecekan usage internet</p>
        </div>
    @endpush

    <div class="space-y-6" x-data="internetApp()" x-init="init()">
        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="stat-card group">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 text-white shadow-lg shadow-blue-200 dark:shadow-blue-900/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m9 0l-2.25 2.25m4.5 0l-2.25-2.25M12 3v1.5m0 0l-2.25-2.25M12 4.5l2.25-2.25M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="badge-info">Total WiFi</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalWifi }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Seluruh data WiFi</p>
            </div>

            <div class="stat-card group">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-200 dark:shadow-emerald-900/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="badge-success">Sudah Dibayar</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $sudahDibayar }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tagihan lunas</p>
            </div>

            <div class="stat-card group">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-200 dark:shadow-amber-900/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="badge-warning">Jatuh Tempo</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $jatuhTempo }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Dalam masa tenggang</p>
            </div>

            <div class="stat-card group">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-lg shadow-red-200 dark:shadow-red-900/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                    </div>
                    <span class="badge-danger">Terlambat</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $terlambat }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Lewat masa tenggang</p>
            </div>
        </div>

        {{-- Tabel: Pembayaran Internet --}}
        <div class="card overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m9 0l-2.25 2.25m4.5 0l-2.25-2.25M12 3v1.5m0 0l-2.25-2.25M12 4.5l2.25-2.25M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Pembayaran Internet
                </h2>

                <div class="flex items-center gap-2 flex-wrap">
                    <div class="relative" x-data="{ search: '' }">
                        <input type="text" x-model="search" @input.debounce="loadPayments(search)" placeholder="Cari..." class="input-field w-40 text-xs pl-8 py-2">
                        <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>

                    <select x-data="{ status: 'semua' }" @change="loadPayments(search, status)" x-model="status" class="input-field text-xs py-2 w-32">
                        <option value="semua">Semua Status</option>
                        <option value="lunas">Lunas</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="terlambat">Terlambat</option>
                    </select>

                    <a href="{{ route('internet.export.payments') }}" class="btn-ghost p-2" title="Download Excel">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    </a>

                    <button @click="$dispatch('open-modal', { name: 'internet-payment-modal' })" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Tagihan
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="table-header">
                            <th class="px-4 py-3 text-center w-12">No</th>
                            <th class="px-4 py-3">Nama Internet</th>
                            <th class="px-4 py-3">Provider</th>
                            <th class="px-4 py-3">PIC</th>
                            <th class="px-4 py-3">Jabatan</th>
                            <th class="px-4 py-3 text-center">Masa Tenggang</th>
                            <th class="px-4 py-3 text-right">Biaya</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Tgl Bayar</th>
                            <th class="px-4 py-3 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($payments as $p)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-900 transition-colors">
                            <td class="table-cell text-center text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                            <td class="table-cell font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ $p->nama_internet }}</td>
                            <td class="table-cell text-gray-600 dark:text-gray-400">{{ $p->provider }}</td>
                            <td class="table-cell text-gray-600 dark:text-gray-400">{{ $p->pic }}</td>
                            <td class="table-cell text-gray-500 dark:text-gray-400">{{ $p->jabatan ?? '-' }}</td>
                            <td class="table-cell text-center whitespace-nowrap">
                                <span class="{{ $p->masa_tenggang < now() && $p->status !== 'lunas' ? 'text-red-600 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                                    {{ $p->masa_tenggang->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="table-cell text-right font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($p->biaya, 0, ',', '.') }}</td>
                            <td class="table-cell text-center">
                                @php
                                    $badge = match($p->status) {
                                        'lunas' => 'badge-success',
                                        'menunggu' => 'badge-warning',
                                        'terlambat' => 'badge-danger',
                                        default => 'badge-info',
                                    };
                                @endphp
                                <span class="{{ $badge }}">{{ ucfirst($p->status) }}</span>
                            </td>
                            <td class="table-cell text-center text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $p->tgl_bayar?->format('d/m/Y') ?? '-' }}</td>
                            <td class="table-cell text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button @click="$dispatch('open-detail', { id: {{ $p->id }} })" class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </button>
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" @click.outside="open = false" class="p-1.5 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Aksi">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                                        </button>
                                        <div x-show="open" x-cloak @click="open = false"
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 -translate-y-2"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             class="absolute right-0 top-full mt-1 min-w-[140px] bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1.5 z-50">
                                            <form method="POST" action="{{ route('internet.destroy.payment', $p) }}" @submit.prevent="$store.confirmModal.show('Hapus Tagihan', 'Apakah Anda yakin ingin menghapus tagihan ini?', () => $el.submit())">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="flex w-full items-center gap-2 px-3 py-2 text-sm font-medium text-red-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-50 dark:bg-gray-900 mb-3">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m9 0l-2.25 2.25m4.5 0l-2.25-2.25M12 3v1.5m0 0l-2.25-2.25M12 4.5l2.25-2.25M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Belum Ada Tagihan</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Belum ada data pembayaran internet.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel: Pengecekan Usage Internet --}}
        <div class="card overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"/></svg>
                    Pengecekan Usage Internet
                </h2>

                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                        {{ now()->format('F Y') }}
                    </span>

                    <a href="{{ route('internet.export.checks', ['month' => now()->month, 'year' => now()->year]) }}" class="btn-ghost p-2" title="Download Excel">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    </a>

                    <button @click="$dispatch('open-modal', { name: 'internet-check-modal' })" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Input Usage
                    </button>
                </div>
            </div>

            <div class="px-6 py-3 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50">
                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    Lakukan pengecekan usage internet per ruangan setiap hari.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="table-header">
                            <th class="px-4 py-3 text-center w-12">No</th>
                            <th class="px-4 py-3">Ruangan</th>
                            <th class="px-4 py-3">Hari</th>
                            <th class="px-4 py-3 text-center">Tanggal</th>
                            <th class="px-4 py-3 text-right">Penggunaan Wifi/Hari</th>
                            <th class="px-4 py-3 text-right">Penggunaan Ethernet/Hari</th>
                            <th class="px-4 py-3">Pengecek</th>
                            <th class="px-4 py-3">Keterangan</th>
                            <th class="px-4 py-3 text-center w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($checks as $c)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-900 transition-colors">
                            <td class="table-cell text-center text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                            <td class="table-cell font-medium text-gray-900 dark:text-gray-100">{{ $c->ruangan }}</td>
                            <td class="table-cell text-gray-600 dark:text-gray-400">{{ $c->hari }}</td>
                            <td class="table-cell text-center text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $c->tanggal->format('d/m/Y') }}</td>
                            <td class="table-cell text-right font-semibold text-gray-900 dark:text-gray-100">{{ number_format($c->penggunaan_wifi, 1) }}</td>
                            <td class="table-cell text-right font-semibold text-gray-900 dark:text-gray-100">{{ number_format($c->penggunaan_ethernet, 1) }}</td>
                            <td class="table-cell text-gray-600 dark:text-gray-400">{{ $c->checker?->name }}</td>
                            <td class="table-cell text-gray-500 dark:text-gray-400 max-w-[150px] truncate">{{ $c->keterangan ?? '-' }}</td>
                            <td class="table-cell text-center">
                                <form method="POST" action="{{ route('internet.destroy.check', $c) }}" @submit.prevent="$store.confirmModal.show('Hapus Pengecekan', 'Apakah Anda yakin ingin menghapus data pengecekan ini?', () => $el.submit())" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-50 dark:bg-gray-900 mb-3">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"/></svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Belum Ada Pengecekan</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Lakukan pengecekan usage internet setiap hari.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal: Tambah Tagihan Internet --}}
    <x-modal name="internet-payment-modal" maxWidth="lg">
        <form method="POST" action="{{ route('internet.store.payment') }}" class="p-6">
            @csrf
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tambah Tagihan Internet</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Isi data tagihan WiFi / internet</p>
                </div>
                <button type="button" @click="$dispatch('close-modal', { name: 'internet-payment-modal' })" class="rounded-xl p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="nama_internet">Nama Internet</x-input-label>
                        <x-text-input id="nama_internet" name="nama_internet" class="w-full mt-1" required placeholder="WiFi 1" />
                    </div>
                    <div>
                        <x-input-label for="provider">Provider</x-input-label>
                        <x-text-input id="provider" name="provider" class="w-full mt-1" required placeholder="Indosat" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="pic">PIC</x-input-label>
                        <x-text-input id="pic" name="pic" class="w-full mt-1" required placeholder="Nama penanggung jawab" />
                    </div>
                    <div>
                        <x-input-label for="jabatan">Jabatan</x-input-label>
                        <x-text-input id="jabatan" name="jabatan" class="w-full mt-1" placeholder="Koordinator" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="masa_tenggang">Masa Tenggang</x-input-label>
                        <x-text-input id="masa_tenggang" name="masa_tenggang" type="date" class="w-full mt-1" required />
                    </div>
                    <div>
                        <x-input-label for="biaya">Biaya (Rp)</x-input-label>
                        <x-text-input id="biaya" name="biaya" type="number" step="0.01" min="0" class="w-full mt-1" required placeholder="0" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="status">Status</x-input-label>
                        <select id="status" name="status" class="input-field w-full mt-1">
                            <option value="menunggu">Menunggu</option>
                            <option value="lunas">Lunas</option>
                            <option value="terlambat">Terlambat</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="tgl_bayar">Tgl Bayar</x-input-label>
                        <x-text-input id="tgl_bayar" name="tgl_bayar" type="date" class="w-full mt-1" />
                    </div>
                </div>

                <div>
                    <x-input-label for="keterangan">Keterangan</x-input-label>
                    <textarea id="keterangan" name="keterangan" rows="2" class="input-field w-full mt-1" placeholder="Opsional"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="button" @click="$dispatch('close-modal', { name: 'internet-payment-modal' })" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </x-modal>

    {{-- Modal: Input Usage Internet --}}
    <x-modal name="internet-check-modal" maxWidth="lg">
        <form method="POST" action="{{ route('internet.store.check') }}" class="p-6">
            @csrf
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Input Usage Internet</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Catat penggunaan internet per ruangan</p>
                </div>
                <button type="button" @click="$dispatch('close-modal', { name: 'internet-check-modal' })" class="rounded-xl p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="ruangan">Ruangan</x-input-label>
                        <x-text-input id="ruangan" name="ruangan" class="w-full mt-1" required placeholder="Ruang Server" />
                    </div>
                    <div>
                        <x-input-label for="hari">Hari</x-input-label>
                        <select id="hari" name="hari" class="input-field w-full mt-1" required>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                </div>

                <div>
                    <x-input-label for="tanggal">Tanggal</x-input-label>
                    <x-text-input id="tanggal" name="tanggal" type="date" class="w-full mt-1" value="{{ now()->format('Y-m-d') }}" required />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="penggunaan_wifi">Penggunaan Wifi (GB)</x-input-label>
                        <x-text-input id="penggunaan_wifi" name="penggunaan_wifi" type="number" step="0.01" min="0" class="w-full mt-1" required placeholder="0" />
                    </div>
                    <div>
                        <x-input-label for="penggunaan_ethernet">Penggunaan Ethernet (GB)</x-input-label>
                        <x-text-input id="penggunaan_ethernet" name="penggunaan_ethernet" type="number" step="0.01" min="0" class="w-full mt-1" required placeholder="0" />
                    </div>
                </div>

                <div>
                    <x-input-label for="keterangan_check">Keterangan</x-input-label>
                    <textarea id="keterangan_check" name="keterangan" rows="2" class="input-field w-full mt-1" placeholder="Opsional"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="button" @click="$dispatch('close-modal', { name: 'internet-check-modal' })" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    <script>
        function internetApp() {
            return {
                init() {},
                async loadPayments(search = '', status = 'semua') {
                    try {
                        const params = new URLSearchParams({ search, status });
                        if (!search) params.delete('search');
                        const res = await fetch(`{{ route('internet.payments.data') }}?${params}`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        const json = await res.json();
                        const tbody = document.querySelector('#payments-table-body');
                        // ... would update table dynamically
                    } catch (e) {
                        console.error('Failed to load payments:', e);
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
