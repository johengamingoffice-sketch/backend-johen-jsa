@push('topbar-left')
    <div>
        <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Manual Book</h1>
        <p class="text-xs text-gray-400 mt-0.5">Panduan & jobdesk karyawan</p>
    </div>
@endpush

<x-app-layout title="Manual Book">

<div x-data="{ showPdfModal: false, openPdf: '' }">
    <div class="card p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($books as $book)
            @php
                $bgMap = ['red' => 'bg-red-50 dark:bg-red-900/20', 'blue' => 'bg-blue-50 dark:bg-blue-900/20', 'emerald' => 'bg-emerald-50 dark:bg-emerald-900/20'];
                $textMap = ['red' => 'text-red-500', 'blue' => 'text-blue-500', 'emerald' => 'text-emerald-500'];
                $color = $book['icon_color'];
            @endphp
            <div @click="openPdf = '{{ $book['file'] }}'; showPdfModal = true"
                 class="flex flex-col items-center text-center p-6 rounded-xl border-2 border-gray-100 dark:border-gray-700 hover:border-primary-200 dark:hover:border-primary-800 hover:bg-primary-50/30 dark:hover:bg-primary-900/5 hover:-translate-y-0.5 hover:shadow-lg cursor-pointer transition-all duration-200 group">
                <div class="w-16 h-16 flex items-center justify-center rounded-2xl {{ $bgMap[$color] ?? 'bg-gray-50 dark:bg-gray-900/20' }} group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-8 h-8 {{ $textMap[$color] ?? 'text-gray-500' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <div class="mt-4">
                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $book['title'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $book['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- PDF Preview Modal --}}
    <div x-show="showPdfModal" x-cloak
         class="fixed inset-0 z-50 flex items-start justify-center p-4 pt-10 bg-gray-900/60 backdrop-blur-sm overflow-y-auto"
         @click="showPdfModal = false">
        <div @click.stop class="relative w-full max-w-4xl rounded-2xl bg-white dark:bg-gray-800 shadow-2xl my-10">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 capitalize" x-text="'Manual Book — ' + openPdf.replace('.pdf', '').replace('book-', '')">Manual Book</h3>
                <button @click="showPdfModal = false" class="rounded-xl p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <iframe :src="'/storage/manual-books/' + openPdf" class="w-full h-[80vh] rounded-b-2xl" frameborder="0"></iframe>
        </div>
    </div>
</div>

</x-app-layout>
