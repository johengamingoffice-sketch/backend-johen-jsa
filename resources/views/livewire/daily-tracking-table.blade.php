<div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 text-white shadow-lg shadow-blue-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
                </div>
                <span class="badge-info">Total</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalSold, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Sold</p>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-teal-500 to-emerald-500 text-white shadow-lg shadow-teal-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5"/></svg>
                </div>
                <span class="badge-success">Total</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalView, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total View</p>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-red-500 text-white shadow-lg shadow-amber-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5"/></svg>
                </div>
                <span class="badge-warning">Total</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalPeak, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Peak</p>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-green-500 text-white shadow-lg shadow-emerald-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="badge-success">Total</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalDurasi, 0, ',', '.') }} Jam</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Durasi</p>
        </div>
    </div>

    <div class="card">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 px-6 py-4 border-b border-gray-50 dark:border-gray-800">
            <div>
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Data Daily Tracking</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tracking harian seluruh divisi game</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 px-6 py-4 border-b border-gray-50 dark:border-gray-800">
            <div class="relative flex-1 min-w-[200px] max-w-xs">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari NIK atau Nama..." class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 pl-9 pr-3 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 focus:border-primary-400 focus:ring-2 focus:ring-primary-100 outline-none transition-all duration-200">
            </div>
            <select wire:model.live="bulan" class="rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 pl-3 pr-8 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 focus:border-primary-400 focus:ring-2 focus:ring-primary-100 outline-none transition-all duration-200">
                <option value="">Semua Bulan</option>
                @foreach(range(1, 12) as $m)
                    @php $val = now()->format('Y') . '-' . str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                    <option value="{{ $val }}">{{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}</option>
                @endforeach
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="table-header">
                        <th class="px-6 py-3 text-center border-r border-gray-200 dark:border-gray-600">Tanggal</th>
                        <th class="px-6 py-3">Divisi</th>
                        <th class="px-6 py-3">NIK</th>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Sesi</th>
                        <th class="px-6 py-3 text-right">Sold</th>
                        <th class="px-6 py-3 text-right">View</th>
                        <th class="px-6 py-3 text-right">Peak View</th>
                        <th class="px-6 py-3 text-center">Durasi Live</th>
                        <th class="px-6 py-3">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse($groupedItems as $date => $dateItems)
                        @foreach($dateItems as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            @if($loop->first)
                            <td class="table-cell text-gray-700 dark:text-gray-300 font-medium text-center border-r border-gray-200 dark:border-gray-600" rowspan="{{ $dateItems->count() }}">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                            @endif
                            <td class="table-cell">
                                <span class="inline-flex items-center gap-1 rounded-lg px-2 py-0.5 text-[11px] font-semibold
                                    {{ match($item->divisi) {
                                        'PUBG' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        'Free Fire' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                        'MLBB' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                        'E-football' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                        'Valorant' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                        default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                    } }}">{{ $item->divisi }}</span>
                            </td>
                            <td class="table-cell font-mono text-xs text-gray-600 dark:text-gray-400">{{ $item->nik }}</td>
                            <td class="table-cell font-medium text-gray-900 dark:text-gray-100">{{ $item->nama }}</td>
                            <td class="table-cell text-gray-600 dark:text-gray-400">{{ $item->sesi ?? '-' }}</td>
                            <td class="table-cell text-right font-mono text-sm text-gray-700 dark:text-gray-300">{{ number_format($item->ach_sold, 0) }}</td>
                            <td class="table-cell text-right font-mono text-sm text-gray-700 dark:text-gray-300">{{ $item->divisi === 'Admin' ? '-' : number_format($item->ach_view, 0) }}</td>
                            <td class="table-cell text-right font-mono text-sm text-gray-700 dark:text-gray-300">{{ $item->divisi === 'Admin' ? '-' : number_format($item->peak_view, 0) }}</td>
                            <td class="table-cell text-center text-gray-600 dark:text-gray-400">{{ $item->divisi === 'Admin' ? '-' : ($item->durasi ? number_format($item->durasi, 0) . ' Jam' : '-') }}</td>
                            <td class="table-cell text-gray-500 dark:text-gray-400 max-w-[150px] truncate">{{ $item->catatan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-10 h-10 mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                                    <p class="font-medium">Belum ada data</p>
                                    <p class="text-xs mt-1">Belum ada data daily tracking untuk bawahan Anda</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="px-6 py-4 border-t border-gray-50 dark:border-gray-800">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>