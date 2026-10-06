<section class="space-y-6">
    <header>
        <h2 class="text-lg font-extrabold text-gray-900">
            {{ __('Hapus Akun') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ __('Setelah akun dihapus, seluruh data akun akan dihapus permanen.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center gap-2 py-2.5 px-5 rounded-xl font-bold text-sm text-white bg-red-600 hover:bg-red-700 shadow-md shadow-red-200 transition-all"
    >{{ __('Hapus Akun') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-extrabold text-gray-900">
                {{ __('Yakin ingin menghapus akun Anda?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                {{ __('Seluruh data akan dihapus permanen. Masukkan kata sandi untuk konfirmasi.') }}
            </p>

            <div class="mt-6">
                <label for="password" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">{{ __('Kata Sandi') }}</label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full sm:w-3/4 border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-2.5 text-sm font-medium transition-all outline-none"
                    placeholder="{{ __('Kata Sandi') }}"
                />

                <p class="mt-2 text-sm text-red-600">{{ $errors->userDeletion->first('password') }}</p>
            </div>

            <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                        class="inline-flex justify-center items-center px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors w-full sm:w-auto">
                    {{ __('Batal') }}
                </button>

                <button class="inline-flex justify-center items-center px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-red-600 hover:bg-red-700 transition-colors shadow-md shadow-red-200 w-full sm:w-auto">
                    {{ __('Hapus Akun') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
