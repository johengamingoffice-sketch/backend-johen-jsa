<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <div class="stat-card">
            <div class="flex items-center gap-3 mb-2">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-lg shadow-primary-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</p>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total'] }}</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-3 mb-2">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-green-500 text-white shadow-lg shadow-emerald-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Terkirim</p>
            </div>
            <p class="text-2xl font-bold text-emerald-600">{{ $stats['sent'] }}</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-3 mb-2">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-lg shadow-amber-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pending</p>
            </div>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-3 mb-2">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-rose-500 text-white shadow-lg shadow-red-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                </div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gagal</p>
            </div>
            <p class="text-2xl font-bold text-red-600">{{ $stats['failed'] }}</p>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100 text-primary-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                </div>
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Detail Pengiriman</h2>
            </div>
            <div class="flex items-center gap-2">
                <select wire:model.live="filter" class="rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 focus:border-primary-400 focus:ring-2 focus:ring-primary-100 outline-none transition-all duration-200">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="sent">Terkirim</option>
                    <option value="failed">Gagal</option>
                </select>
                <span wire:poll.5s class="inline-flex items-center gap-1.5 text-xs text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-800 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Live
                </span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="table-header">
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Waktu Kirim</th>
                        <th class="px-6 py-3">Error</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($details as $detail)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:bg-gray-800 transition-colors">
                            <td class="table-cell font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ $detail->nama }}</td>
                            <td class="table-cell text-gray-600 dark:text-gray-400">{{ $detail->email }}</td>
                            <td class="table-cell whitespace-nowrap">
                                @if($detail->status === 'sent')
                                    <span class="badge-success">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Terkirim
                                    </span>
                                @elseif($detail->status === 'failed')
                                    <span class="badge-danger">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Gagal
                                    </span>
                                @else
                                    <span class="badge-warning">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="table-cell text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                {{ $detail->emailLog?->sent_at ? $detail->emailLog->sent_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="table-cell max-w-[200px]">
                                @if($detail->emailLog?->error_message)
                                    <span class="text-xs text-red-600 truncate block sm:max-w-[200px]" title="{{ $detail->emailLog->error_message }}">
                                        {{ Str::limit($detail->emailLog->error_message, 50) }}
                                    </span>
                                @else
                                    <span class="text-gray-300 dark:text-gray-600">-</span>
                                @endif
                            </td>
                            <td class="table-cell text-center whitespace-nowrap">
                                @if($detail->status === 'failed')
                                    <form action="{{ route('payroll.email-retry', $detail) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 text-xs font-medium text-primary-600 hover:text-primary-500 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                                            Kirim Ulang
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-300 dark:text-gray-600 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data pengiriman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


