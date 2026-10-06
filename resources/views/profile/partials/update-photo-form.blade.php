<section>
    <header>
        <h2 class="text-lg font-extrabold text-gray-900">
            {{ __('Ubah Foto Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ __('Unggah foto baru atau hapus foto profil Anda. Format JPG, PNG, atau WebP, maksimal 2MB.') }}
        </p>
    </header>

    <div class="mt-6 flex flex-col sm:flex-row sm:items-center gap-6">
        {{-- Preview --}}
        <div class="shrink-0">
            @if($user->photo)
                <img src="{{ Storage::url($user->photo) }}" alt="Foto Profil"
                     class="w-24 h-24 rounded-2xl object-cover border-2 border-white shadow-md">
            @else
                <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-700 flex items-center justify-center text-white text-3xl font-extrabold shadow-md">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
        </div>

        {{-- Forms --}}
        <div class="flex-1 min-w-0 space-y-4">
            <form method="post" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data"
                  class="flex flex-col sm:flex-row sm:items-center gap-3">
                @csrf

                <label for="photo"
                       class="flex-1 inline-flex items-center justify-center gap-2 cursor-pointer border border-dashed border-gray-300 bg-gray-50 hover:bg-green-50 hover:border-green-400 rounded-xl px-4 py-3 text-sm font-semibold text-gray-600 transition-colors w-full sm:w-auto">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span id="photo-filename">Pilih foto...</span>
                    <input id="photo" name="photo" type="file" class="hidden" accept="image/jpeg,image/png,image/webp"
                           onchange="document.getElementById('photo-filename').textContent = this.files[0] ? this.files[0].name : 'Pilih foto...'" />
                </label>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 py-3 px-5 rounded-xl font-bold text-sm text-white shadow-md shadow-green-200 transition-all"
                        style="background:linear-gradient(135deg,#22c55e,#16a34a)">
                    {{ __('Unggah') }}
                </button>
            </form>

            @error('photo', 'photoUpdate')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            @if($user->photo)
                <form method="post" action="{{ route('profile.photo.destroy') }}"
                      onsubmit="return confirm('Hapus foto profil Anda?');">
                    @csrf
                    @method('delete')
                    <button type="submit"
                            class="text-sm font-bold text-red-500 hover:text-red-700 underline underline-offset-2 transition-colors">
                        {{ __('Hapus foto profil') }}
                    </button>
                </form>
            @endif

            @if (session('status') === 'photo-updated')
                <p class="text-sm font-semibold text-green-600">{{ __('Foto profil berhasil diperbarui.') }}</p>
            @elseif (session('status') === 'photo-removed')
                <p class="text-sm font-semibold text-green-600">{{ __('Foto profil berhasil dihapus.') }}</p>
            @endif
        </div>
    </div>
</section>
