<x-guest-layout>
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-gray-900 border border-gray-100 dark:border-gray-800 p-8">
        <div class="text-center mb-8">
            <div class="inline-flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-primary-600 to-violet-600 text-white font-bold text-xl mb-4 shadow-lg shadow-primary-200">
                P
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Konfirmasi Password</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Konfirmasi password untuk melanjutkan</p>
        </div>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Ini adalah area aman aplikasi. Harap konfirmasi password Anda sebelum melanjutkan.
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
            @csrf

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5">Password</label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    <input id="password" type="password" name="password" required autocomplete="current-password" class="input-field pl-10" placeholder="Masukkan password">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <button type="submit" class="btn-primary w-full justify-center">
                Konfirmasi
            </button>
        </form>
    </div>
</x-guest-layout>





