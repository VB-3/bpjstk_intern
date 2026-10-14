<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.tasks.index') }}"
                class="p-2 rounded-xl text-gray-500 hover:text-green-700 hover:bg-green-50 transition-colors"
                title="Kembali ke Daftar Tugas">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                Edit Tugas
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
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold leading-tight">Edit Detail Tugas</h3>
                            <p class="text-xs text-green-200 mt-0.5">{{ $task->title }}</p>
                        </div>
                    </div>
                </div>

                {{-- Form Content --}}
                <div class="p-8 bg-white/80">
                    <form method="POST" action="{{ route('superadmin.tasks.update', $task) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Judul Tugas --}}
                        <div>
                            <label for="title" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                                Judul Tugas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" required
                                value="{{ old('title', $task->title) }}"
                                placeholder="Contoh: Laporan Mingguan Evaluasi Program JKK"
                                class="w-full border @error('title') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-2.5 text-sm font-medium transition-all outline-none">
                            @error('title')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Deskripsi Tugas --}}
                        <div>
                            <label for="description" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                                Deskripsi Tugas
                            </label>
                            <textarea id="description" name="description" rows="4"
                                placeholder="Deskripsikan instruksi tugas, kriteria pengumpulan, atau format berkas yang diharapkan..."
                                class="w-full border @error('description') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-3 text-sm font-medium transition-all outline-none resize-none leading-relaxed">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Batas Waktu (Deadline) --}}
                        <div>
                            <label for="deadline" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                                Batas Waktu Pengumpulan (Deadline)
                            </label>
                            <input type="datetime-local" id="deadline" name="deadline"
                                value="{{ old('deadline', $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('Y-m-d\TH:i') : '') }}"
                                class="w-full border @error('deadline') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-2.5 text-sm font-medium transition-all outline-none">
                            @error('deadline')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Aktif Checkbox --}}
                        <div class="pt-2">
                            <label class="relative flex items-start gap-3 p-4 rounded-2xl bg-gray-50/80 border border-gray-200 cursor-pointer hover:bg-green-50/40 hover:border-green-300 transition-colors">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" id="is_active" name="is_active" value="1"
                                        {{ old('is_active', $task->is_active) ? 'checked' : '' }}
                                        class="w-5 h-5 rounded-lg border-gray-300 text-green-600 focus:ring-green-500 focus:ring-2 transition-colors">
                                </div>
                                <div class="text-sm">
                                    <span class="font-bold text-gray-800">Aktifkan tugas ini</span>
                                    <p class="text-xs text-gray-500 mt-0.5">Jika dicentang, tugas dapat diakses dan dikerjakan oleh peserta. Beberapa tugas bisa aktif sekaligus.</p>
                                </div>
                            </label>
                            @error('is_active')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('superadmin.tasks.index') }}"
                                class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-bold transition-colors text-center w-full sm:w-auto">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 rounded-xl text-white font-bold text-sm shadow-md shadow-green-200 hover:shadow-lg transition-all justify-center w-full sm:w-auto"
                                style="background: linear-gradient(135deg, #16a34a, #15803d);">
                                Perbarui Tugas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
