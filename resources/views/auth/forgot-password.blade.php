<x-guest-layout>
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-gray-900 border border-gray-100 dark:border-gray-800 p-8">
        <div class="text-center mb-8">
            <div class="inline-flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-primary-600 to-violet-600 text-white font-bold text-xl mb-4 shadow-lg shadow-primary-200">
                P
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Lupa Password</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Masukkan email untuk mereset password</p>
        </div>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Lupa password Anda? Tidak masalah. Beritahu kami alamat email Anda dan kami akan mengirimkan tautan reset password.
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5">Email</label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    <input id="email" type="email" name="email" required autofocus class="input-field pl-10" placeholder="email@company.com">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <button type="submit" class="btn-primary w-full justify-center">
                Kirim Tautan Reset
            </button>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500 transition-colors">Kembali ke login</a>
            </p>
        </form>
    </div>
</x-guest-layout>





