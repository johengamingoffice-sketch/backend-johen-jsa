<x-app-layout title="Aset Digital">
    @push('topbar-left')
        <div>
            <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Aset Digital</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Kelola tagihan aset digital dan pembayarannya</p>
        </div>
    @endpush

    <div class="space-y-6">
        @php
            $total = max($totalNominal, 1);
            $pctLunas = round($nominalLunas / $total * 100);
            $pctMenunggu = round($nominalMenunggu / $total * 100);
            $pctTerlambat = 100 - $pctLunas - $pctMenunggu;
            $donut = $pctLunas * 3.6;
            $donut2 = $donut + $pctMenunggu * 3.6;
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
            <div class="stat-card group">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 text-white shadow-lg shadow-blue-200 dark:shadow-blue-900/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3"/></svg>
                    </div>
                    <span class="badge-info">Total Aset Digital</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalAset }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $totalAset }} tagihan</p>
            </div>

            <div class="stat-card group">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-200 dark:shadow-emerald-900/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="badge-success">Sudah Dibayar</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalLunas }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tagihan lunas</p>
            </div>

            <div class="stat-card group">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-200 dark:shadow-amber-900/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="badge-warning">Jatuh Tempo</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalJatuhTempo }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Belum dibayar</p>
            </div>

            <div class="stat-card group">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-lg shadow-red-200 dark:shadow-red-900/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                    </div>
                    <span class="badge-danger">Terlambat</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalTerlambat }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Lewat jatuh tempo</p>
            </div>

            <div class="stat-card group flex flex-col">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Nominal</span>
                    <span class="badge-info">Rp {{ number_format($totalNominal, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center gap-4 flex-1" x-data>
                    <div class="relative w-20 h-20 shrink-0">
                        <svg class="w-20 h-20 -rotate-90" viewBox="0 0 36 36">
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="currentColor" stroke-width="2.8" class="text-gray-100 dark:text-gray-800"/>
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="currentColor" stroke-width="2.8" stroke-dasharray="{{ $pctLunas }} {{ 100 - $pctLunas }}" stroke-dashoffset="0" class="text-emerald-500"/>
                            @if($pctMenunggu > 0)
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="currentColor" stroke-width="2.8" stroke-dasharray="{{ $pctMenunggu }} {{ 100 - $pctMenunggu }}" stroke-dashoffset="{{ -$pctLunas }}" class="text-amber-500"/>
                            @endif
                            @if($pctTerlambat > 0)
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="currentColor" stroke-width="2.8" stroke-dasharray="{{ $pctTerlambat }} {{ 100 - $pctTerlambat }}" stroke-dashoffset="{{ -$pctLunas - $pctMenunggu }}" class="text-red-500"/>
                            @endif
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-xs font-bold text-gray-900 dark:text-gray-100">Rp{{ number_format($totalNominal / 1000000, 1) }}jt</span>
                        </div>
                    </div>
                    <div class="space-y-1 text-xs flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                            <span class="truncate text-gray-500">Lunas</span>
                            <span class="ml-auto font-semibold text-gray-900 dark:text-gray-100">Rp{{ number_format($nominalLunas, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                            <span class="truncate text-gray-500">Menunggu</span>
                            <span class="ml-auto font-semibold text-gray-900 dark:text-gray-100">Rp{{ number_format($nominalMenunggu, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-500 shrink-0"></span>
                            <span class="truncate text-gray-500">Terlambat</span>
                            <span class="ml-auto font-semibold text-gray-900 dark:text-gray-100">Rp{{ number_format($nominalTerlambat, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3"/></svg>
                    Pembayaran Aset Digital
                </h2>

                <div class="flex items-center gap-2 flex-wrap">
                    <div class="relative" x-data="{ search: '' }">
                        <input type="text" x-model="search" @input.debounce="loadAssets(search)" placeholder="Cari..." class="input-field w-40 text-xs pl-8 py-2">
                        <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>

                    <select x-data="{ status: 'semua' }" @change="loadAssets(search, status)" x-model="status" class="input-field text-xs py-2 w-32">
                        <option value="semua">Semua Status</option>
                        <option value="lunas">Lunas</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="terlambat">Terlambat</option>
                    </select>

                    <a href="{{ route('digital.export') }}" class="btn-ghost p-2" title="Download Excel">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    </a>

                    <button @click="$dispatch('open-modal', { name: 'digital-asset-modal' })" class="btn-primary">
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
                            <th class="px-4 py-3">Nama Aset</th>
                            <th class="px-4 py-3">Tagihan</th>
                            <th class="px-4 py-3 text-center">Jatuh Tempo</th>
                            <th class="px-4 py-3 text-right">Nominal</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Tgl Bayar</th>
                            <th class="px-4 py-3 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($assets as $a)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-900 transition-colors">
                            <td class="table-cell text-center text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                            <td class="table-cell font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ $a->nama_aset }}</td>
                            <td class="table-cell text-gray-600 dark:text-gray-400">{{ $a->tagihan }}</td>
                            <td class="table-cell text-center whitespace-nowrap">
                                <span class="{{ $a->jatuh_tempo < now() && $a->status !== 'lunas' ? 'text-red-600 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                                    {{ $a->jatuh_tempo->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="table-cell text-right font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($a->nominal, 0, ',', '.') }}</td>
                            <td class="table-cell text-center">
                                @php
                                    $badge = match($a->status) {
                                        'lunas' => 'badge-success',
                                        'menunggu' => 'badge-warning',
                                        'terlambat' => 'badge-danger',
                                        default => 'badge-info',
                                    };
                                @endphp
                                <span class="{{ $badge }}">{{ ucfirst($a->status) }}</span>
                            </td>
                            <td class="table-cell text-center text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $a->tgl_bayar?->format('d/m/Y') ?? '-' }}</td>
                            <td class="table-cell text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @if($a->status !== 'lunas')
                                    <form method="POST" action="{{ route('digital.mark-paid', $a) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="p-1.5 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition-colors" title="Tandai Lunas">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" @click.outside="open = false" class="p-1.5 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Aksi">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                                        </button>
                                        <div x-show="open" x-cloak @click="open = false"
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 -translate-y-2"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             class="absolute right-0 top-full mt-1 min-w-[140px] bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1.5 z-50">
                                            <form method="POST" action="{{ route('digital.destroy', $a) }}" @submit.prevent="$store.confirmModal.show('Hapus Tagihan', 'Apakah Anda yakin ingin menghapus tagihan ini?', () => $el.submit())">
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
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-50 dark:bg-gray-900 mb-3">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3"/></svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Belum Ada Data</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Belum ada tagihan aset digital.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($assets->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $assets->links() }}
            </div>
            @endif
        </div>
    </div>

    <x-modal name="digital-asset-modal" maxWidth="lg">
        <form method="POST" action="{{ route('digital.store') }}" class="p-6">
            @csrf
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tambah Tagihan Aset Digital</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Isi data tagihan aset digital</p>
                </div>
                <button type="button" @click="$dispatch('close-modal', { name: 'digital-asset-modal' })" class="rounded-xl p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="nama_aset">Nama Aset</x-input-label>
                        <x-text-input id="nama_aset" name="nama_aset" class="w-full mt-1" required placeholder="Capcut" />
                    </div>
                    <div>
                        <x-input-label for="tagihan">Tagihan</x-input-label>
                        <x-text-input id="tagihan" name="tagihan" class="w-full mt-1" required placeholder="Langganan Pro" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="jatuh_tempo">Jatuh Tempo</x-input-label>
                        <x-text-input id="jatuh_tempo" name="jatuh_tempo" type="date" class="w-full mt-1" required />
                    </div>
                    <div>
                        <x-input-label for="nominal">Nominal (Rp)</x-input-label>
                        <x-text-input id="nominal" name="nominal" type="number" step="0.01" min="0" class="w-full mt-1" required placeholder="0" />
                    </div>
                </div>

                <div>
                    <x-input-label for="keterangan">Keterangan</x-input-label>
                    <textarea id="keterangan" name="keterangan" rows="2" class="input-field w-full mt-1" placeholder="Opsional"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="button" @click="$dispatch('close-modal', { name: 'digital-asset-modal' })" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    <script>
        function digitalApp() {
            return {
                init() {},
                async loadAssets(search = '', status = 'semua') {
                    try {
                        const params = new URLSearchParams();
                        if (search) params.set('search', search);
                        if (status && status !== 'semua') params.set('status', status);
                        const res = await fetch(`{{ route('digital.data') }}?${params}`, {
                            headers: { 'Accept': 'application/json' }
                        });
                    } catch (e) {
                        console.error('Failed to load assets:', e);
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
