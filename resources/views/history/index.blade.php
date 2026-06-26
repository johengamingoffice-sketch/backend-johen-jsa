<x-app-layout title="Riwayat Payroll">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Riwayat Payroll</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Semua data payroll yang pernah diupload</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="relative">
                <select onchange="window.location = '?year=' + this.value"
                        class="appearance-none rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 pl-3 pr-8 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 focus:border-primary-400 focus:ring-2 focus:ring-primary-100 outline-none transition-all duration-200 cursor-pointer">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </div>
            <a href="{{ route('payroll.upload') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Upload Baru
            </a>
        </div>
    </div>

    @if($imports->count() > 0)
        <div class="card divide-y divide-gray-100 dark:divide-gray-800 overflow-hidden">
            @foreach($imports as $import)
                <a href="{{ route('history.show', $import) }}" class="block p-5 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-100 to-violet-100 text-primary-600 flex-shrink-0">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $import->periode }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $import->file_name }}</p>
                                    <div class="flex items-center flex-wrap gap-x-4 gap-y-1 mt-2">
                                        <span class="badge-info text-xs">{{ $import->payroll_details_count }} karyawan</span>
                                        <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Rp {{ number_format($import->total_payroll, 0, ',', '.') }}</span>
                                        <span class="text-xs text-gray-400 dark:text-gray-500">{{ $import->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-400 dark:text-gray-500 hidden sm:inline">oleh {{ $import->uploadedBy?->name ?? 'Unknown' }}</span>
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-400 dark:text-gray-500 group-hover:bg-primary-50 group-hover:text-primary-500 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                </div>
                            </div>
                        </div>
                    </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $imports->links() }}
        </div>
    @else
        <div class="card">
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="flex h-24 w-24 items-center justify-center rounded-2xl bg-gray-50 dark:bg-gray-900 mb-5">
                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Belum ada riwayat payroll</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Upload payroll pertama Anda untuk memulai</p>
                <a href="{{ route('payroll.upload') }}" class="btn-primary mt-5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Upload Payroll Pertama
                </a>
            </div>
        </div>
    @endif

</x-app-layout>


