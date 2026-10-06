<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="page-wrapper">
        <div class="max-w-3xl mx-auto space-y-8">

            {{-- Foto Profil --}}
            <div class="glass-effect rounded-3xl overflow-hidden shadow-md flex flex-col">
                <div class="section-header">
                    <div class="w-9 h-9 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-base font-extrabold text-gray-900">Foto Profil</h3>
                </div>
                <div class="p-6 sm:p-8 bg-white/60">
                    @include('profile.partials.update-photo-form')
                </div>
            </div>

            {{-- Informasi Profil --}}
            <div class="glass-effect rounded-3xl overflow-hidden shadow-md flex flex-col">
                <div class="section-header">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="text-base font-extrabold text-gray-900">Informasi Profil</h3>
                </div>
                <div class="p-6 sm:p-8 bg-white/60">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Kata Sandi --}}
            <div class="glass-effect rounded-3xl overflow-hidden shadow-md flex flex-col">
                <div class="section-header">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h3 class="text-base font-extrabold text-gray-900">Keamanan Akun</h3>
                </div>
                <div class="p-6 sm:p-8 bg-white/60">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Hapus Akun --}}
            <div class="glass-effect rounded-3xl overflow-hidden shadow-md flex flex-col">
                <div class="section-header">
                    <div class="w-9 h-9 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <h3 class="text-base font-extrabold text-gray-900">Hapus Akun</h3>
                </div>
                <div class="p-6 sm:p-8 bg-white/60">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
