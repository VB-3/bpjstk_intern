<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.todos.index') }}"
                class="p-2 rounded-xl text-gray-500 hover:text-green-700 hover:bg-green-50 transition-colors"
                title="Kembali ke Daftar To-Do">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                Edit To-Do - {{ $todo->user->name }}
            </h2>
        </div>
    </x-slot>

    <div class="page-wrapper">
        <div class="max-w-2xl mx-auto">
            <div class="glass-effect rounded-3xl overflow-hidden shadow-lg border border-white/60">
                {{-- Card Header Banner --}}
                <div class="relative overflow-hidden px-8 py-6 text-white"
                    style="background: linear-gradient(135deg, #16a34a 0%, #15803d 50%, #166534 100%);">
                    <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-white/10 pointer-events-none"></div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center font-extrabold text-lg shadow-sm">
                            {{ strtoupper(substr($todo->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold leading-tight">{{ $todo->user->name }}</h3>
                            <p class="text-xs text-green-200 mt-0.5">{{ $todo->user->email }}</p>
                        </div>
                    </div>
                    @if($todo->user && $todo->user->gdrive_link)
                        <a href="{{ $todo->user->formatted_gdrive_link ?? $todo->user->gdrive_link }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold bg-white text-green-900 hover:bg-green-50 shadow-sm transition-all ml-auto">
                            <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Buka Google Drive Peserta
                        </a>
                    @endif
                </div>

                {{-- Form Content --}}
                <div class="p-8 bg-white/80">
                    <form method="POST" action="{{ route('superadmin.todos.update', $todo) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Tanggal --}}
                        <div>
                            <label for="date" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                                Tanggal Kegiatan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="date" name="date" required
                                value="{{ old('date', $todo->date ? \Carbon\Carbon::parse($todo->date)->format('Y-m-d') : '') }}"
                                class="w-full border @error('date') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-2.5 text-sm font-medium transition-all outline-none">
                            @error('date')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Rangkuman Kegiatan --}}
                        <div>
                            <label for="summary" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                                Rangkuman Kegiatan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="summary" name="summary" rows="5" required
                                placeholder="Tuliskan rangkuman kegiatan yang dilakukan peserta..."
                                class="w-full border @error('summary') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-3 text-sm font-medium transition-all outline-none resize-none leading-relaxed">{{ old('summary', $todo->summary) }}</textarea>
                            @error('summary')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Capaian BPU --}}
                        <div>
                            <label for="bpu_achievement" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                                Pencapaian BPU Harian (Orang) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" id="bpu_achievement" name="bpu_achievement" min="0" required
                                    value="{{ old('bpu_achievement', $todo->bpu_achievement ?? 0) }}"
                                    class="w-full border @error('bpu_achievement') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-2.5 text-sm font-medium transition-all outline-none">
                            </div>
                            <p class="mt-1.5 text-xs text-gray-400">Jumlah perolehan peserta Bukan Penerima Upah (BPU) yang didaftarkan hari ini.</p>
                            @error('bpu_achievement')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('superadmin.todos.index') }}"
                                class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-bold transition-colors text-center w-full sm:w-auto">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 rounded-xl text-white font-bold text-sm shadow-md shadow-green-200 hover:shadow-lg transition-all justify-center w-full sm:w-auto"
                                style="background: linear-gradient(135deg, #16a34a, #15803d);">
                                Perbarui To-Do
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
