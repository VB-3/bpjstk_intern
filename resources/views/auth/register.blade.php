<x-guest-layout :fixed="true">
    <div class="mb-3 sm:mb-4 text-center">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Daftar Akun Baru</h2>
        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Buat akun untuk mengakses Portal Magang BPJS Ketenagakerjaan.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-2.5 sm:space-y-3">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <label for="name" class="block font-medium text-xs sm:text-sm text-gray-700 mb-1">{{ __('Nama Lengkap') }}</label>
            <input id="name" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-3.5 py-2 sm:py-2.5 bg-gray-50/50 text-xs sm:text-sm" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama lengkap Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs" />
        </div>

        <!-- Email & NIM (2 Kolom) -->
        <div class="grid grid-cols-2 gap-2.5">
            <div>
                <label for="email" class="block font-medium text-xs sm:text-sm text-gray-700 mb-1">{{ __('Email') }}</label>
                <input id="email" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-3.5 py-2 sm:py-2.5 bg-gray-50/50 text-xs sm:text-sm" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="alamat@email.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
            </div>
            <div>
                <label for="nim" class="block font-medium text-xs sm:text-sm text-gray-700 mb-1">{{ __('NIM') }}</label>
                <input id="nim" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-3.5 py-2 sm:py-2.5 bg-gray-50/50 text-xs sm:text-sm" type="text" name="nim" value="{{ old('nim') }}" required placeholder="Contoh: 12345678" />
                <x-input-error :messages="$errors->get('nim')" class="mt-1 text-xs" />
            </div>
        </div>

        <!-- Mentor -->
        <div>
            <label for="mentor_id" class="block font-medium text-xs sm:text-sm text-gray-700 mb-1">{{ __('Pilih Mentor') }}</label>
            <select id="mentor_id" name="mentor_id" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-3.5 py-2 sm:py-2.5 bg-gray-50/50 text-xs sm:text-sm" required>
                <option value="">-- Pilih Mentor Anda --</option>
                @foreach($mentors as $mentor)
                    <option value="{{ $mentor->id }}" {{ old('mentor_id') == $mentor->id ? 'selected' : '' }}>{{ $mentor->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('mentor_id')" class="mt-1 text-xs" />
        </div>

        <!-- Password & Konfirmasi Password (2 Kolom) -->
        <div class="grid grid-cols-2 gap-2.5">
            <div>
                <label for="password" class="block font-medium text-xs sm:text-sm text-gray-700 mb-1">{{ __('Password') }}</label>
                <input id="password" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-3.5 py-2 sm:py-2.5 bg-gray-50/50 text-xs sm:text-sm"
                                type="password"
                                name="password"
                                required autocomplete="new-password" placeholder="Min. 8 karakter" />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
            </div>
            <div>
                <label for="password_confirmation" class="block font-medium text-xs sm:text-sm text-gray-700 mb-1">{{ __('Ulangi Password') }}</label>
                <input id="password_confirmation" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-3.5 py-2 sm:py-2.5 bg-gray-50/50 text-xs sm:text-sm"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-2.5 sm:py-3 px-4 border border-transparent rounded-xl shadow-md text-xs sm:text-sm font-bold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                {{ __('Daftar Sekarang') }}
            </button>
        </div>

        <div class="text-center pt-1">
            <p class="text-xs sm:text-sm text-gray-600">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-semibold text-green-600 hover:text-green-500 transition">Masuk di sini</a>
            </p>
        </div>
    </form>
</x-guest-layout>
