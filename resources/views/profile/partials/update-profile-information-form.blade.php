<section>
    <header>
        <h2 class="text-lg font-extrabold text-gray-900">
            {{ __('Informasi Akun') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            @if(auth()->user()->isIntern())
                {{ __('Perbarui nama akun Anda.') }}
            @else
                {{ __('Perbarui nama dan alamat email akun Anda.') }}
            @endif
        </p>
    </header>

    @if(!auth()->user()->isIntern())
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
    @endif

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">{{ __('Nama') }}</label>
            <input id="name" name="name" type="text"
                   class="w-full border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-2.5 text-sm font-medium transition-all outline-none"
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        @if(!auth()->user()->isIntern())
        <div>
            <label for="email" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">{{ __('Email') }}</label>
            <input id="email" name="email" type="email"
                   class="w-full border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-2.5 text-sm font-medium transition-all outline-none"
                   value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            {{ __('Klik di sini untuk mengirim ulang tautan verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
        @endif

        <div class="flex items-center gap-4">
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 py-2.5 px-5 rounded-xl font-bold text-sm text-white shadow-md shadow-green-200 transition-all"
                    style="background:linear-gradient(135deg,#22c55e,#16a34a)">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm font-semibold text-green-600">{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>

