<x-app-layout title="Persetujuan Pembayaran">
    @push('topbar-left')
        <div>
            <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Persetujuan Pembayaran</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Pengajuan pembayaran yang menunggu persetujuan</p>
        </div>
    @endpush

    <div class="space-y-6">
        <div class="card overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                    Persetujuan
                </h2>

                <div class="flex items-center gap-2 flex-wrap">
                    <div class="relative" x-data="{ search: '' }">
                        <input type="text" x-model="search" @input.debounce="loadPersetujuan(search)" placeholder="Cari..." class="input-field w-40 text-xs pl-8 py-2">
                        <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>

                    <select x-data="{ jenis: 'semua' }" @change="loadPersetujuan(search, jenis)" x-model="jenis" class="input-field text-xs py-2 w-32">
                        <option value="semua">Semua Jenis</option>
                        <option value="Aset Digital">Aset Digital</option>
                        <option value="IPL Ruko">IPL Ruko</option>
                        <option value="Listrik">Listrik</option>
                        <option value="Internet">Internet</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>

                    <a href="{{ route('payment-submissions.export.persetujuan') }}" class="btn-ghost p-2" title="Download Excel">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="table-header">
                            <th class="px-4 py-3 text-center w-12">No</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Pengaju</th>
                            <th class="px-4 py-3">Jenis</th>
                            <th class="px-4 py-3">Detail</th>
                            <th class="px-4 py-3 text-right">Nominal</th>
                            <th class="px-4 py-3 text-center">Tgl Bayar</th>
                            <th class="px-4 py-3 text-center">Bukti</th>
                            <th class="px-4 py-3 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($submissions as $s)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-900 transition-colors">
                            <td class="table-cell text-center text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                            <td class="table-cell text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $s->created_at->format('d/m/Y') }}</td>
                            <td class="table-cell font-medium text-gray-900 dark:text-gray-100">{{ $s->pengaju?->name }}</td>
                            <td class="table-cell text-gray-600 dark:text-gray-400">{{ $s->jenis }}</td>
                            <td class="table-cell text-gray-600 dark:text-gray-400 max-w-[150px] truncate">{{ $s->detail }}</td>
                            <td class="table-cell text-right font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($s->nominal, 0, ',', '.') }}</td>
                            <td class="table-cell text-center text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $s->tgl_bayar?->format('d/m/Y') ?? '-' }}</td>
                            <td class="table-cell text-center">
                                @if($s->bukti)
                                <a href="{{ asset('storage/' . $s->bukti) }}" target="_blank" class="text-blue-600 hover:underline text-xs">Lihat</a>
                                @else
                                <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="table-cell text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <form method="POST" action="{{ route('payment-submissions.approve', $s) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="p-1.5 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition-colors" title="Setujui">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('payment-submissions.reject', $s) }}" class="inline" onsubmit="return confirm('Tolak pengajuan ini?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Tolak">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-50 dark:bg-gray-900 mb-3">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Tidak Ada Pengajuan</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Semua pengajuan sudah diproses.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($submissions->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $submissions->links() }}
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function persetujuanApp() {
            return {
                init() {},
                async loadPersetujuan(search = '', jenis = 'semua') {
                    try {
                        const params = new URLSearchParams();
                        if (search) params.set('search', search);
                        if (jenis && jenis !== 'semua') params.set('jenis', jenis);
                        const res = await fetch(`{{ route('payment-submissions.data.persetujuan') }}?${params}`, {
                            headers: { 'Accept': 'application/json' }
                        });
                    } catch (e) {
                        console.error('Failed to load persetujuan:', e);
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
