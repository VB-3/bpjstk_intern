<x-guest-layout :fixed="true">
    <!-- Session Status -->
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <div class="mb-6 sm:mb-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Masuk ke Akun Anda</h2>
        <p class="text-sm text-gray-500 mt-2">Silakan masukkan NIM dan password untuk melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-5">
        @csrf

        <!-- NIM (or Email for admin/mentor) -->
        <div>
            <label for="login" class="block font-medium text-sm text-gray-700 mb-1.5">{{ __('NIM') }}</label>
            <input id="login" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-4 py-3 bg-gray-50/50 text-sm" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username" placeholder="Masukkan NIM Anda" />
            <x-input-error :messages="$errors->get('login')" class="mt-1.5 text-xs sm:text-sm" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block font-medium text-sm text-gray-700">{{ __('Password') }}</label>
            </div>

            <input id="password" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition px-4 py-3 bg-gray-50/50 text-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs sm:text-sm" />
        </div>

        <!-- Remember Me -->
        <div class="block pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 cursor-pointer w-4 h-4" name="remember">
                <span class="ms-2 text-sm text-gray-600 font-medium">{{ __('Ingat Saya') }}</span>
            </label>
        </div>

        <div class="pt-3">
            <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                {{ __('Masuk') }}
            </button>
        </div>
        
        <div class="text-center pt-2">
            <p class="text-sm text-gray-600">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-semibold text-green-600 hover:text-green-500 transition">Daftar sekarang</a>
            </p>
        </div>
    </form>
</x-guest-layout>
