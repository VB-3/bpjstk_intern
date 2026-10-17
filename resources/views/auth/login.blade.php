<x-guest-layout :fixed="true">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-4 sm:mb-5 text-center">
        <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">Masuk ke Akun Anda</h2>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Silakan masukkan email atau NIM dan password.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-3.5 sm:space-y-4">
        @csrf

        <!-- Email Address or NIM -->
        <div>
            <label for="login" class="block font-semibold text-xs sm:text-sm text-gray-700 mb-1">{{ __('Email atau NIM') }}</label>
            <input id="login" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-3.5 py-2.5 sm:py-3 bg-gray-50/50 text-sm" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username" placeholder="alamat@email.com atau NIM" />
            <x-input-error :messages="$errors->get('login')" class="mt-1 text-xs" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block font-semibold text-xs sm:text-sm text-gray-700">{{ __('Password') }}</label>
            </div>

            <input id="password" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-3.5 py-2.5 sm:py-3 bg-gray-50/50 text-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
        </div>

        <!-- Remember Me -->
        <div class="block pt-0.5">
            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 cursor-pointer w-4 h-4" name="remember">
                <span class="ms-2 text-xs sm:text-sm text-gray-600 font-medium">{{ __('Ingat Saya') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-2.5 sm:py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                {{ __('Masuk') }}
            </button>
        </div>
        
        <div class="text-center pt-1">
            <p class="text-xs sm:text-sm text-gray-600">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-semibold text-green-600 hover:text-green-500 transition">Daftar sekarang</a>
            </p>
        </div>
    </form>
</x-guest-layout>
