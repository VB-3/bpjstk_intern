<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.attendances.index') }}"
                class="p-2 rounded-xl text-gray-500 hover:text-green-700 hover:bg-green-50 transition-colors"
                title="Kembali ke Daftar Absensi">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                Edit Absensi - {{ $attendance->user->name }}
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
                            {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold leading-tight">{{ $attendance->user->name }}</h3>
                            <p class="text-xs text-green-200 mt-0.5">{{ $attendance->user->email }}</p>
                        </div>
                    </div>
                </div>

                {{-- Form Content --}}
                <div class="p-8 bg-white/80">
                    <form method="POST" action="{{ route('superadmin.attendances.update', $attendance) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Tanggal --}}
                        <div>
                            <label for="date" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                                Tanggal Absensi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="date" name="date" required
                                value="{{ old('date', $attendance->date ? \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') : '') }}"
                                class="w-full border @error('date') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-2.5 text-sm font-medium transition-all outline-none">
                            @error('date')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tipe Absensi --}}
                        <div>
                            <label for="type" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                                Tipe Absensi <span class="text-red-500">*</span>
                            </label>
                            <select id="type" name="type" required
                                class="w-full border @error('type') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-2.5 text-sm font-medium transition-all outline-none">
                                <option value="harian" {{ old('type', $attendance->type) === 'harian' ? 'selected' : '' }}>Absensi Harian</option>
                                <option value="luar_harian" {{ old('type', $attendance->type) === 'luar_harian' ? 'selected' : '' }}>Kegiatan di Luar Harian</option>
                            </select>
                            @error('type')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Check-In & Check-Out Times --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="check_in_time" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                                    Waktu Check-In
                                </label>
                                <input type="datetime-local" id="check_in_time" name="check_in_time"
                                    value="{{ old('check_in_time', $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('Y-m-d\TH:i') : '') }}"
                                    class="w-full border @error('check_in_time') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-2.5 text-sm font-medium transition-all outline-none">
                                @error('check_in_time')
                                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="check_out_time" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                                    Waktu Check-Out
                                </label>
                                <input type="datetime-local" id="check_out_time" name="check_out_time"
                                    value="{{ old('check_out_time', $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('Y-m-d\TH:i') : '') }}"
                                    class="w-full border @error('check_out_time') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-2.5 text-sm font-medium transition-all outline-none">
                                @error('check_out_time')
                                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        {{-- Action Buttons --}}
                        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('superadmin.attendances.index') }}"
                                class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-bold transition-colors text-center w-full sm:w-auto">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 rounded-xl text-white font-bold text-sm shadow-md shadow-green-200 hover:shadow-lg transition-all justify-center w-full sm:w-auto"
                                style="background: linear-gradient(135deg, #16a34a, #15803d);">
                                Perbarui Absensi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
