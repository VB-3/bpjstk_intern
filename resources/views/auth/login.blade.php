<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Masuk ke Akun Anda</h2>
        <p class="text-sm text-gray-500 mt-2">Silakan masukkan email dan password untuk melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="login" class="block font-medium text-sm text-gray-700">{{ __('Email atau NIM') }}</label>
            <input id="login" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-4 py-3 bg-gray-50/50" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username" placeholder="alamat@email.com atau 12345678" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block font-medium text-sm text-gray-700">{{ __('Password') }}</label>
            </div>

            <input id="password" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-4 py-3 bg-gray-50/50"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-2">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 cursor-pointer" name="remember">
                <span class="ms-2 text-sm text-gray-600 font-medium">{{ __('Ingat Saya') }}</span>
            </label>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                {{ __('Masuk') }}
            </button>
        </div>
        
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-semibold text-green-600 hover:text-green-500 transition">Daftar sekarang</a>
            </p>
        </div>
    </form>
</x-guest-layout>

