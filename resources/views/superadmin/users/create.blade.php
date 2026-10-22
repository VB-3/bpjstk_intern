<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Tambah Pengguna Baru
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
                <div class="px-6 sm:px-8 py-6 bg-gradient-to-r from-green-600 to-green-700 text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-white">Formulir Pengguna Baru</h3>
                            <p class="text-green-100 text-xs mt-0.5">Lengkapi formulir di bawah ini untuk menambahkan akun pengguna baru ke portal.</p>
                        </div>
                    </div>
                </div>

                {{-- Form Body --}}
                <div class="p-6 sm:p-8 bg-white/80">
                    <form method="POST" action="{{ route('superadmin.users.store') }}" class="space-y-6" id="create-user-form">
                        @csrf

                        {{-- Nama --}}
                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')" class="font-bold text-gray-700" />
                            <x-text-input id="name"
                                          name="name"
                                          type="text"
                                          class="block w-full mt-1.5 border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm transition-colors text-sm py-2.5 px-4"
                                          :value="old('name')"
                                          required
                                          autofocus
                                          placeholder="Masukkan nama lengkap pengguna" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Role --}}
                        <div>
                            <x-input-label for="role" :value="__('Peran (Role)')" class="font-bold text-gray-700" />
                            <select id="role"
                                    name="role"
                                    required
                                    onchange="toggleRoleFields(this.value)"
                                    class="block w-full mt-1.5 border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm transition-colors text-sm py-2.5 px-4 font-medium text-gray-700 outline-none">
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih peran...</option>
                                <option value="intern" {{ old('role') === 'intern' ? 'selected' : '' }}>Peserta Magang</option>
                                <option value="mentor" {{ old('role') === 'mentor' ? 'selected' : '' }}>Mentor</option>
                                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        {{-- ── Bidang untuk NON-INTERN (Mentor / Super Admin) ── --}}
                        <div id="field-email" class="{{ old('role', '') === 'intern' ? 'hidden' : '' }}">
                            <x-input-label for="email" :value="__('Alamat Email')" class="font-bold text-gray-700" />
                            <x-text-input id="email"
                                          name="email"
                                          type="email"
                                          class="block w-full mt-1.5 border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm transition-colors text-sm py-2.5 px-4"
                                          :value="old('email')"
                                          placeholder="contoh: pengguna@bpjsketenagakerjaan.go.id" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- ── Bidang untuk INTERN ── --}}
                        <div id="field-intern-extra" class="{{ old('role', '') !== 'intern' ? 'hidden' : '' }} space-y-4">
                            {{-- NIM --}}
                            <div>
                                <x-input-label for="nim" :value="__('NIM (9 Digit)')" class="font-bold text-gray-700" />
                                <x-text-input id="nim"
                                              name="nim"
                                              type="text"
                                              class="block w-full mt-1.5 border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm transition-colors text-sm py-2.5 px-4"
                                              :value="old('nim')"
                                              maxlength="9"
                                              minlength="9"
                                              inputmode="numeric"
                                              oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,9)"
                                              placeholder="9 digit angka" />
                                <x-input-error :messages="$errors->get('nim')" class="mt-2" />
                            </div>

                            {{-- Mentor --}}
                            <div>
                                <x-input-label for="mentor_id" :value="__('Pilih Mentor')" class="font-bold text-gray-700" />
                                <select id="mentor_id"
                                        name="mentor_id"
                                        class="block w-full mt-1.5 border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm transition-colors text-sm py-2.5 px-4 font-medium text-gray-700 outline-none">
                                    <option value="">-- Pilih Mentor --</option>
                                    @foreach($mentors as $mentor)
                                        <option value="{{ $mentor->id }}" {{ old('mentor_id') == $mentor->id ? 'selected' : '' }}>{{ $mentor->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('mentor_id')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Password --}}
                        <div>
                            <x-input-label for="password" :value="__('Kata Sandi (Password)')" class="font-bold text-gray-700" />
                            <x-text-input id="password"
                                          name="password"
                                          type="password"
                                          class="block w-full mt-1.5 border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm transition-colors text-sm py-2.5 px-4"
                                          required
                                          autocomplete="new-password"
                                          placeholder="Minimal 8 karakter" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        {{-- Password Confirmation --}}
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="font-bold text-gray-700" />
                            <x-text-input id="password_confirmation"
                                          name="password_confirmation"
                                          type="password"
                                          class="block w-full mt-1.5 border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm transition-colors text-sm py-2.5 px-4"
                                          required
                                          autocomplete="new-password"
                                          placeholder="Ulangi kata sandi" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-4 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 border-t border-gray-100">
                            <a href="{{ route('superadmin.users.index') }}"
                               class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-bold transition-colors text-center w-full sm:w-auto">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-6 py-2.5 rounded-xl text-white font-bold text-sm shadow-md shadow-green-200 bg-green-600 hover:bg-green-700 transition-all flex items-center gap-2 justify-center w-full sm:w-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Simpan Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleRoleFields(role) {
            const emailField  = document.getElementById('field-email');
            const internExtra = document.getElementById('field-intern-extra');
            const nimInput    = document.getElementById('nim');
            const mentorInput = document.getElementById('mentor_id');
            const emailInput  = document.getElementById('email');

            if (role === 'intern') {
                emailField.classList.add('hidden');
                internExtra.classList.remove('hidden');
                emailInput.removeAttribute('required');
                nimInput.setAttribute('required', '');
                mentorInput.setAttribute('required', '');
            } else {
                emailField.classList.remove('hidden');
                internExtra.classList.add('hidden');
                emailInput.setAttribute('required', '');
                nimInput.removeAttribute('required');
                mentorInput.removeAttribute('required');
            }
        }

        // Jalankan saat halaman load (untuk kasus old() setelah validasi gagal)
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            if (roleSelect.value) {
                toggleRoleFields(roleSelect.value);
            }
        });
    </script>
</x-app-layout>
