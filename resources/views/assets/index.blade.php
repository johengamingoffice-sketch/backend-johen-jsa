<x-app-layout :title="$selectedCategory ? ucfirst($selectedCategory) : 'Data Asset'">
    @push('topbar-left')
        <div>
            <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                {{ $selectedCategory ? ucfirst($selectedCategory) : 'Data Asset' }}
            </h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Kelola data asset perusahaan</p>
        </div>
    @endpush

    <div class="space-y-6">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('assets.index') }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 {{ !$selectedCategory ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                Semua
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('assets.category', strtolower($cat->name)) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 {{ $selectedCategory && strtolower($cat->name) === strtolower($selectedCategory) ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <div class="card overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Daftar Asset
                </h2>
                <div class="flex items-center gap-2">
                    <form method="GET" action="{{ route('assets.index') }}" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..."
                               class="input-field w-40 text-xs pl-8 py-2">
                        <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="table-header">
                            <th class="px-4 py-3 text-center w-12">No</th>
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Nama Asset</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Brand</th>
                            <th class="px-4 py-3 text-center">Kondisi</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-right w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($assets as $a)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-900 transition-colors">
                            <td class="table-cell text-center text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                            <td class="table-cell font-mono text-xs text-gray-500 dark:text-gray-400">{{ $a->code }}</td>
                            <td class="table-cell font-medium text-gray-900 dark:text-gray-100">{{ $a->name }}</td>
                            <td class="table-cell">
                                <span class="badge-info">{{ $a->category?->name ?? '-' }}</span>
                            </td>
                            <td class="table-cell text-gray-600 dark:text-gray-400">{{ $a->brand ?? '-' }}</td>
                            <td class="table-cell text-center">
                                @php
                                    $conditionLabels = ['baik' => 'Baik', 'rusak_ringan' => 'Rusak Ringan', 'rusak_berat' => 'Rusak Berat'];
                                    $conditionClasses = ['baik' => 'badge-success', 'rusak_ringan' => 'badge-warning', 'rusak_berat' => 'badge-danger'];
                                @endphp
                                <span class="{{ $conditionClasses[$a->condition] ?? 'badge-info' }}">{{ $conditionLabels[$a->condition] ?? $a->condition }}</span>
                            </td>
                            <td class="table-cell text-center">
                                @php
                                    $statusLabels = ['tersedia' => 'Tersedia', 'dipinjam' => 'Dipinjam', 'dalam_perbaikan' => 'Perbaikan', 'dihapuskan' => 'Dihapuskan'];
                                    $statusClasses = ['tersedia' => 'badge-success', 'dipinjam' => 'badge-warning', 'dalam_perbaikan' => 'badge-danger', 'dihapuskan' => 'badge-info'];
                                @endphp
                                <span class="{{ $statusClasses[$a->status] ?? 'badge-info' }}">{{ $statusLabels[$a->status] ?? $a->status }}</span>
                            </td>
                            <td class="table-cell text-right">
                                <a href="#" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-50 dark:bg-gray-900 mb-3">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Belum Ada Asset</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Belum ada data asset di kategori ini.</p>
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
</x-app-layout>
