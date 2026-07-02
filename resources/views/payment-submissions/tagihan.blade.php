<x-app-layout title="Tagihan Pembayaran">
    @push('topbar-left')
        <div>
            <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Tagihan Pembayaran</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Daftar tagihan yang perlu dibayar</p>
        </div>
    @endpush

    <div class="space-y-6">
        <div class="card overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                    Tagihan Pembayaran
                </h2>

                <div class="flex items-center gap-2 flex-wrap">
                    <div class="relative" x-data="{ search: '' }">
                        <input type="text" x-model="search" @input.debounce="loadTagihan(search)" placeholder="Cari..." class="input-field w-40 text-xs pl-8 py-2">
                        <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>

                    <select x-data="{ jenis: 'semua' }" @change="loadTagihan(search, jenis)" x-model="jenis" class="input-field text-xs py-2 w-32">
                        <option value="semua">Semua Jenis</option>
                        <option value="Aset Digital">Aset Digital</option>
                        <option value="IPL Ruko">IPL Ruko</option>
                        <option value="Listrik">Listrik</option>
                        <option value="Internet">Internet</option>
                    </select>

                    <a href="{{ route('payment-submissions.export.tagihan') }}" class="btn-ghost p-2" title="Download Excel">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="table-header">
                            <th class="px-4 py-3 text-center w-12">No</th>
                            <th class="px-4 py-3">Jenis</th>
                            <th class="px-4 py-3">Detail</th>
                            <th class="px-4 py-3 text-right">Nominal</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($submissions as $s)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-900 transition-colors">
                            <td class="table-cell text-center text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                            <td class="table-cell font-medium text-gray-900 dark:text-gray-100">{{ $s->jenis }}</td>
                            <td class="table-cell text-gray-600 dark:text-gray-400">{{ $s->detail }}</td>
                            <td class="table-cell text-right font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($s->nominal, 0, ',', '.') }}</td>
                            <td class="table-cell text-center">
                                @php
                                    $badge = match($s->status) {
                                        'lunas' => 'badge-success',
                                        'disetujui', 'jatuh_tempo' => 'badge-warning',
                                        default => 'badge-info',
                                    };
                                @endphp
                                <span class="{{ $badge }}">{{ ucfirst($s->status) }}</span>
                            </td>
                            <td class="table-cell text-center">
                                @if($s->status !== 'lunas')
                                <form method="POST" action="{{ route('payment-submissions.mark-paid', $s) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-primary text-xs px-3 py-1.5">Bayar</button>
                                </form>
                                @else
                                <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-50 dark:bg-gray-900 mb-3">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Tidak Ada Tagihan</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Semua tagihan sudah dibayar.</p>
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
        function tagihanApp() {
            return {
                init() {},
                async loadTagihan(search = '', jenis = 'semua') {
                    try {
                        const params = new URLSearchParams();
                        if (search) params.set('search', search);
                        if (jenis && jenis !== 'semua') params.set('jenis', jenis);
                        const res = await fetch(`{{ route('payment-submissions.data.tagihan') }}?${params}`, {
                            headers: { 'Accept': 'application/json' }
                        });
                    } catch (e) {
                        console.error('Failed to load tagihan:', e);
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
