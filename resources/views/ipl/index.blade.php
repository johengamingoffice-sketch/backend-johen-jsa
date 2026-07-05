<x-app-layout title="IPL Ruko">
    @push('topbar-left')
        <div>
            <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">IPL Ruko</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Kelola tagihan IPL Ruko</p>
        </div>
    @endpush

    <div class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="stat-card group">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 text-white shadow-lg shadow-blue-200 dark:shadow-blue-900/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
                    </div>
                    <span class="badge-info">Total Tagihan</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalTagihan }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $totalTagihan }} tagihan</p>
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
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalMenunggu }}</p>
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
        </div>

        <div class="card overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
                    Pembayaran IPL Ruko
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

                    <a href="{{ route('ipl.export') }}" class="btn-ghost p-2" title="Download Excel">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    </a>

                    <button @click="$dispatch('open-modal', { name: 'ipl-payment-modal' })" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Tagihan
                    </button>

                    <button @click="$dispatch('open-modal', { name: 'ipl-generate-modal' })" class="btn-secondary">
                        Generate 1 Tahun
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="table-header">
                            <th class="px-4 py-3 text-center w-12">No</th>
                            <th class="px-4 py-3">Periode</th>
                            <th class="px-4 py-3">Tagihan</th>
                            <th class="px-4 py-3 text-center">Jatuh Tempo</th>
                            <th class="px-4 py-3 text-right">Nominal</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Tgl Bayar</th>
                            <th class="px-4 py-3 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($payments as $p)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-900 transition-colors">
                            <td class="table-cell text-center text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                            <td class="table-cell font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ $p->periode }}</td>
                            <td class="table-cell text-gray-600 dark:text-gray-400">{{ $p->tagihan }}</td>
                            <td class="table-cell text-center whitespace-nowrap">
                                <span class="{{ $p->jatuh_tempo < now() && $p->status !== 'lunas' ? 'text-red-600 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                                    {{ $p->jatuh_tempo->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="table-cell text-right font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
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
                                    @if($p->status !== 'lunas')
                                    <form method="POST" action="{{ route('ipl.mark-paid', $p) }}" class="inline">
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
                                            <form method="POST" action="{{ route('ipl.destroy', $p) }}" @submit.prevent="$store.confirmModal.show('Hapus Tagihan', 'Apakah Anda yakin ingin menghapus tagihan ini?', () => $el.submit())">
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
                                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Belum Ada Data</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Belum ada tagihan IPL Ruko.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $payments->links() }}
            </div>
            @endif
        </div>
    </div>

    <x-modal name="ipl-payment-modal" maxWidth="lg">
        <form method="POST" action="{{ route('ipl.store') }}" class="p-6">
            @csrf
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tambah Tagihan IPL Ruko</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Isi data tagihan IPL Ruko</p>
                </div>
                <button type="button" @click="$dispatch('close-modal', { name: 'ipl-payment-modal' })" class="rounded-xl p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="periode">Periode</x-input-label>
                        <x-text-input id="periode" name="periode" class="w-full mt-1" required placeholder="Juli 2026" />
                    </div>
                    <div>
                        <x-input-label for="tagihan">Tagihan</x-input-label>
                        <x-text-input id="tagihan" name="tagihan" class="w-full mt-1" required placeholder="IPL Ruko Bulanan" />
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
                <button type="button" @click="$dispatch('close-modal', { name: 'ipl-payment-modal' })" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </x-modal>

    <x-modal name="ipl-generate-modal" maxWidth="md">
        <form method="POST" action="{{ route('ipl.generate') }}" class="p-6">
            @csrf
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Generate 1 Tahun</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Generate tagihan IPL Ruko untuk 1 tahun penuh</p>
                </div>
                <button type="button" @click="$dispatch('close-modal', { name: 'ipl-generate-modal' })" class="rounded-xl p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <x-input-label for="tahun">Tahun</x-input-label>
                    <x-text-input id="tahun" name="tahun" type="number" class="w-full mt-1" value="{{ now()->format('Y') }}" min="2020" max="2100" required />
                </div>
                <div>
                    <x-input-label for="tagihan">Nama Tagihan</x-input-label>
                    <x-text-input id="tagihan" name="tagihan" class="w-full mt-1" required placeholder="IPL Ruko" />
                </div>
                <div>
                    <x-input-label for="nominal">Nominal per Bulan (Rp)</x-input-label>
                    <x-text-input id="nominal" name="nominal" type="number" step="0.01" min="0" class="w-full mt-1" required placeholder="0" />
                </div>
                <div>
                    <x-input-label for="jatuh_tempo_hari">Tanggal Jatuh Tempo (1-28)</x-input-label>
                    <x-text-input id="jatuh_tempo_hari" name="jatuh_tempo_hari" type="number" class="w-full mt-1" value="10" min="1" max="28" required />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="button" @click="$dispatch('close-modal', { name: 'ipl-generate-modal' })" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Generate</button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    <script>
        function iplApp() {
            return {
                init() {},
                async loadPayments(search = '', status = 'semua') {
                    try {
                        const params = new URLSearchParams();
                        if (search) params.set('search', search);
                        if (status && status !== 'semua') params.set('status', status);
                        const res = await fetch(`{{ route('ipl.data') }}?${params}`, {
                            headers: { 'Accept': 'application/json' }
                        });
                    } catch (e) {
                        console.error('Failed to load payments:', e);
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
