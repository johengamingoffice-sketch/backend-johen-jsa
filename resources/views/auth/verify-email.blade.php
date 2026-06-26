<x-guest-layout>
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-gray-900 border border-gray-100 dark:border-gray-800 p-8">
        <div class="text-center mb-8">
            <div class="inline-flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-primary-600 to-violet-600 text-white font-bold text-xl mb-4 shadow-lg shadow-primary-200">
                P
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Verifikasi Email</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Verifikasi email Anda untuk mengakses semua fitur</p>
        </div>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Terima kasih telah mendaftar! Sebelum memulai, verifikasi alamat email Anda dengan mengklik tautan yang telah kami kirimkan. Jika tidak menerima email, kami akan dengan senang hati mengirim ulang.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-emerald-600 flex items-center gap-2 bg-emerald-50 rounded-xl px-4 py-3 border border-emerald-100">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Tautan verifikasi baru telah dikirim ke email Anda.
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-primary">
                    Kirim Ulang Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-ghost text-sm">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>





