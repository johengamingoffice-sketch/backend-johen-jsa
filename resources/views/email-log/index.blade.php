<x-app-layout title="Log Email">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Log Pengiriman Email</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Periode {{ $import->periode }}</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('payroll.email-retry-all', $import) }}" method="POST">
                @csrf
                <button type="submit" class="btn-danger">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                    Retry Semua Gagal
                </button>
            </form>
            <a href="{{ route('payroll.show', $import) }}" class="btn-ghost">Kembali</a>
        </div>
    </div>

    @livewire('email-log-monitor', ['import' => $import])

</x-app-layout>


