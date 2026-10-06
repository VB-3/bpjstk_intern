<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Pengguna
            </h2>
            <a href="{{ route('superadmin.users.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-600 hover:text-gray-900 bg-white hover:bg-gray-50 border border-gray-200 px-3.5 py-2 rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="page-wrapper">
        <div class="max-w-2xl mx-auto">
            <div class="glass-effect rounded-3xl overflow-hidden shadow-lg border border-white/60">
                {{-- Card Header --}}
                <div class="px-6 sm:px-8 py-6 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-extrabold text-white">Edit Data Pengguna</h3>
                                <p class="text-blue-100 text-xs mt-0.5">Perbarui profil dan wewenang akun pengguna</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white font-extrabold text-sm shrink-0 border border-white/30">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>
                </div>

                {{-- Form Body --}}
                <div class="p-6 sm:p-8 bg-white/80">
                    <form method="POST" action="{{ route('superadmin.users.update', $user) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Nama --}}
                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')" class="font-bold text-gray-700" />
                            <x-text-input id="name"
                                          name="name"
                                          type="text"
                                          class="block w-full mt-1.5 border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm transition-colors text-sm py-2.5 px-4"
                                          :value="old('name', $user->name)"
                                          required
                                          autofocus
                                          placeholder="Masukkan nama lengkap pengguna" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Email --}}
                        <div>
                            <x-input-label for="email" :value="__('Alamat Email')" class="font-bold text-gray-700" />
                            <x-text-input id="email"
                                          name="email"
                                          type="email"
                                          class="block w-full mt-1.5 border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm transition-colors text-sm py-2.5 px-4"
                                          :value="old('email', $user->email)"
                                          required
                                          placeholder="contoh: pengguna@bpjsketenagakerjaan.go.id" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Role --}}
                        <div>
                            <x-input-label for="role" :value="__('Peran (Role)')" class="font-bold text-gray-700" />
                            <select id="role"
                                    name="role"
                                    required
                                    class="block w-full mt-1.5 border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm transition-colors text-sm py-2.5 px-4 font-medium text-gray-700 outline-none">
                                <option value="intern" {{ old('role', $user->role) === 'intern' ? 'selected' : '' }}>Peserta Magang</option>
                                <option value="mentor" {{ old('role', $user->role) === 'mentor' ? 'selected' : '' }}>Mentor</option>
                                <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        {{-- Password Section with Info Alert --}}
                        <div class="pt-2 border-t border-gray-100 space-y-4">
                            <div class="p-3.5 bg-amber-50/80 border border-amber-200/80 rounded-2xl flex items-start gap-2.5">
                                <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-xs text-amber-800 leading-relaxed font-medium">
                                    <span class="font-bold">Perubahan Kata Sandi:</span> Kosongkan kolom kata sandi di bawah jika Anda tidak ingin mengubah kata sandi pengguna saat ini.
                                </p>
                            </div>

                            {{-- Password --}}
                            <div>
                                <x-input-label for="password" :value="__('Kata Sandi Baru (Opsional)')" class="font-bold text-gray-700" />
                                <x-text-input id="password"
                                              name="password"
                                              type="password"
                                              class="block w-full mt-1.5 border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm transition-colors text-sm py-2.5 px-4"
                                              autocomplete="new-password"
                                              placeholder="Kosongkan jika tidak ingin mengubah password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            {{-- Password Confirmation --}}
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" class="font-bold text-gray-700" />
                                <x-text-input id="password_confirmation"
                                              name="password_confirmation"
                                              type="password"
                                              class="block w-full mt-1.5 border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm transition-colors text-sm py-2.5 px-4"
                                              autocomplete="new-password"
                                              placeholder="Ulangi kata sandi baru" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-4 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 border-t border-gray-100">
                            <a href="{{ route('superadmin.users.index') }}"
                               class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-bold transition-colors text-center w-full sm:w-auto">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-6 py-2.5 rounded-xl text-white font-bold text-sm shadow-md shadow-blue-200 bg-blue-600 hover:bg-blue-700 transition-all flex items-center gap-2 justify-center w-full sm:w-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Perbarui Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

