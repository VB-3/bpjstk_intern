<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Daftar Akun Baru</h2>
        <p class="text-sm text-gray-500 mt-2">Buat akun untuk mengakses Portal Magang BPJS Ketenagakerjaan.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Nama Lengkap') }}</label>
            <input id="name" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-4 py-3 bg-gray-50/50" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block font-medium text-sm text-gray-700">{{ __('Email') }}</label>
            <input id="email" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-4 py-3 bg-gray-50/50" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="alamat@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- NIM -->
        <div>
            <label for="nim" class="block font-medium text-sm text-gray-700">{{ __('NIM') }}</label>
            <input id="nim" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-4 py-3 bg-gray-50/50" type="text" name="nim" value="{{ old('nim') }}" required placeholder="Contoh: 12345678" />
            <x-input-error :messages="$errors->get('nim')" class="mt-2" />
        </div>

        <!-- Mentor -->
        <div>
            <label for="mentor_id" class="block font-medium text-sm text-gray-700">{{ __('Pilih Mentor') }}</label>
            <select id="mentor_id" name="mentor_id" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-4 py-3 bg-gray-50/50" required>
                <option value="">-- Pilih Mentor Anda --</option>
                @foreach($mentors as $mentor)
                    <option value="{{ $mentor->id }}" {{ old('mentor_id') == $mentor->id ? 'selected' : '' }}>{{ $mentor->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('mentor_id')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block font-medium text-sm text-gray-700">{{ __('Password') }}</label>
            <input id="password" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-4 py-3 bg-gray-50/50"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block font-medium text-sm text-gray-700">{{ __('Konfirmasi Password') }}</label>
            <input id="password_confirmation" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-4 py-3 bg-gray-50/50"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                {{ __('Daftar') }}
            </button>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-semibold text-green-600 hover:text-green-500 transition">Masuk di sini</a>
            </p>
        </div>
    </form>
</x-guest-layout>
