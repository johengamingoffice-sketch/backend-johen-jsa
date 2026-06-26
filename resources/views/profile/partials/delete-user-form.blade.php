<section>
    <div class="flex items-center gap-3 mb-6">
        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-100 text-red-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
        </div>
        <div>
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Hapus Akun</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Setelah akun dihapus, semua data akan hilang permanen</p>
        </div>
    </div>

    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
        Setelah akun Anda dihapus, seluruh sumber daya dan data akan dihapus secara permanen. Sebelum menghapus akun, harap unduh data atau informasi yang ingin Anda pertahankan.
    </p>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Hapus Akun</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 mb-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-100 text-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Apakah Anda yakin?</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Setelah akun Anda dihapus, seluruh sumber daya dan data akan dihapus secara permanen. Masukkan password Anda untuk mengonfirmasi penghapusan akun.
            </p>

            <div class="mt-4">
                <x-input-label for="password" value="Password" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="input-field mt-1.5"
                    placeholder="Masukkan password Anda"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="btn-secondary">
                    Batal
                </x-secondary-button>

                <x-danger-button class="btn-danger">
                    Hapus Akun Saya
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>


