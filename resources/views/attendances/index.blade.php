<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Absensi
        </h2>
    </x-slot>

    <div class="page-wrapper" x-data="{ showPhotoModal: false, activePhotoUrl: '', activePhotoTitle: '' }">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-4 px-5 py-3 bg-green-50 border border-green-200 rounded-2xl text-sm text-green-700 font-semibold flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 px-5 py-3 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-700 font-semibold flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            {{-- ── Kolom Kiri: Kamera + Form ──────────────────────────────────── --}}
            <div class="lg:col-span-1 lg:sticky top-24 space-y-4">

                {{-- Webcam Card --}}
                <div class="glass-effect rounded-3xl overflow-hidden shadow-md">
                    <div class="relative overflow-hidden px-6 py-5"
                         style="background: linear-gradient(135deg,#16a34a 0%,#166534 100%)">
                        <div class="absolute -top-8 -right-8 w-28 h-28 rounded-full bg-white/10"></div>
                        <div class="flex items-center gap-3 relative z-10">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-extrabold text-white">Form Absensi</h2>
                                <p class="text-green-200 text-xs mt-0.5">Foto selfie otomatis terdeteksi</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 bg-white/90">
                        <div class="w-full rounded-2xl overflow-hidden border-2 border-gray-100 bg-gray-900 shadow-inner aspect-video mb-1">
                            <video id="webcam" autoplay playsinline class="w-full h-full object-cover"></video>
                        </div>
                        <canvas id="canvas" class="hidden"></canvas>
                        <p class="text-[11px] text-gray-400 text-center mt-2">Kamera akan digunakan untuk foto absensi</p>
                    </div>
                </div>

                {{-- ── Card Absensi HARIAN ─────────────────────────────────── --}}
                <div class="glass-effect rounded-3xl overflow-hidden shadow-md">
                    <div class="px-5 py-4 border-b border-gray-100 bg-white flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-green-100 text-green-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-gray-900">Kegiatan Harian</h3>
                            <p class="text-xs text-gray-400">Absensi rutin harian</p>
                        </div>
                        @if($todayHarian)
                            <span class="ml-auto inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold
                                {{ $todayHarian->check_out_time ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-700' }}">
                                {{ $todayHarian->check_out_time ? '✓ Selesai' : '● Aktif' }}
                            </span>
                        @endif
                    </div>
                    <div class="p-5 bg-white/90 space-y-3">
                        @if(!$todayHarian)
                            {{-- Belum check-in harian --}}
                            <form method="POST" action="{{ route('attendances.checkin') }}" id="form-checkin-harian">
                                @csrf
                                <input type="hidden" name="type" value="harian">
                                <input type="hidden" name="check_in_photo" id="photo-checkin-harian">
                                <button type="button"
                                        onclick="takeSnapshotAndSubmit('form-checkin-harian','photo-checkin-harian')"
                                        class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm text-white shadow-md shadow-green-200 transition-all hover:opacity-90"
                                        style="background:linear-gradient(135deg,#22c55e,#16a34a)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Check In Harian
                                </button>
                            </form>

                        @elseif(!$todayHarian->check_out_time)
                            {{-- Sudah check-in, belum check-out --}}
                            <div class="flex items-center gap-2 p-3 bg-green-50 border border-green-200 rounded-xl text-xs text-green-700 font-semibold">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Check-in {{ \Carbon\Carbon::parse($todayHarian->check_in_time)->format('H:i') }} WIB
                            </div>
                            <form method="POST" action="{{ route('attendances.checkout') }}" id="form-checkout-harian">
                                @csrf
                                <input type="hidden" name="type" value="harian">
                                <input type="hidden" name="check_out_photo" id="photo-checkout-harian">
                                <button type="button"
                                        onclick="takeSnapshotAndSubmit('form-checkout-harian','photo-checkout-harian')"
                                        class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm text-white shadow-md shadow-red-200 transition-all hover:opacity-90"
                                        style="background:linear-gradient(135deg,#f87171,#dc2626)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Check Out Harian
                                </button>
                            </form>

                        @else
                            {{-- Selesai --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-600">
                                <span class="flex items-center gap-1.5 text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Masuk {{ \Carbon\Carbon::parse($todayHarian->check_in_time)->format('H:i') }}
                                </span>
                                <span class="text-gray-300">|</span>
                                <span class="flex items-center gap-1.5 text-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/></svg>
                                    Keluar {{ \Carbon\Carbon::parse($todayHarian->check_out_time)->format('H:i') }}
                                </span>
                            </div>
                            <p class="text-center text-xs text-gray-400 font-medium">✓ Absensi harian hari ini sudah selesai</p>
                        @endif
                    </div>
                </div>

                {{-- ── Card Absensi LUAR HARIAN ─────────────────────────────── --}}
                <div class="glass-effect rounded-3xl overflow-hidden shadow-md">
                    <div class="px-5 py-4 border-b border-gray-100 bg-white flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-gray-900">Kegiatan Luar Harian</h3>
                            <p class="text-xs text-gray-400">Kegiatan di luar rutinitas</p>
                        </div>
                        @if($todayLuarHarian)
                            <span class="ml-auto inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold
                                {{ $todayLuarHarian->check_out_time ? 'bg-gray-100 text-gray-600' : 'bg-purple-100 text-purple-700' }}">
                                {{ $todayLuarHarian->check_out_time ? '✓ Selesai' : '● Aktif' }}
                            </span>
                        @endif
                    </div>
                    <div class="p-5 bg-white/90 space-y-3">
                        @if(!$todayLuarHarian)
                            {{-- Belum check-in luar harian --}}
                            <form method="POST" action="{{ route('attendances.checkin') }}" id="form-checkin-luar">
                                @csrf
                                <input type="hidden" name="type" value="luar_harian">
                                <input type="hidden" name="check_in_photo" id="photo-checkin-luar">
                                <button type="button"
                                        onclick="takeSnapshotAndSubmit('form-checkin-luar','photo-checkin-luar')"
                                        class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm text-white transition-all hover:opacity-90"
                                        style="background:linear-gradient(135deg,#a78bfa,#7c3aed)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Check In Luar Harian
                                </button>
                            </form>

                        @elseif(!$todayLuarHarian->check_out_time)
                            {{-- Sudah check-in, belum check-out --}}
                            <div class="flex items-center gap-2 p-3 bg-purple-50 border border-purple-200 rounded-xl text-xs text-purple-700 font-semibold">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Check-in {{ \Carbon\Carbon::parse($todayLuarHarian->check_in_time)->format('H:i') }} WIB
                            </div>
                            <form method="POST" action="{{ route('attendances.checkout') }}" id="form-checkout-luar">
                                @csrf
                                <input type="hidden" name="type" value="luar_harian">
                                <input type="hidden" name="check_out_photo" id="photo-checkout-luar">
                                <button type="button"
                                        onclick="takeSnapshotAndSubmit('form-checkout-luar','photo-checkout-luar')"
                                        class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm text-white transition-all hover:opacity-90"
                                        style="background:linear-gradient(135deg,#c084fc,#9333ea)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Check Out Luar Harian
                                </button>
                            </form>

                        @else
                            {{-- Selesai --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-600">
                                <span class="flex items-center gap-1.5 text-purple-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Masuk {{ \Carbon\Carbon::parse($todayLuarHarian->check_in_time)->format('H:i') }}
                                </span>
                                <span class="text-gray-300">|</span>
                                <span class="flex items-center gap-1.5 text-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/></svg>
                                    Keluar {{ \Carbon\Carbon::parse($todayLuarHarian->check_out_time)->format('H:i') }}
                                </span>
                            </div>
                            <p class="text-center text-xs text-gray-400 font-medium">✓ Absensi luar harian hari ini sudah selesai</p>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ── Kolom Kanan: Riwayat ───────────────────────────────────────── --}}
            <div class="lg:col-span-2">
                <div class="glass-effect rounded-3xl overflow-hidden shadow-md flex flex-col">
                    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100/70 bg-white/80">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <h2 class="text-base font-extrabold text-gray-900">Riwayat Absensi</h2>
                        </div>
                        <span class="text-xs text-gray-400 font-medium">{{ $dates->total() }} hari tercatat</span>
                    </div>

                    <div class="p-5 flex-1 bg-gray-50/40">
                        <div class="space-y-3">
                        @forelse($dates as $dateObj)
                            @php
                                $dateStr = $dateObj->date instanceof \Carbon\Carbon ? $dateObj->date->format('Y-m-d') : (string)$dateObj->date;
                                $dayAttendances = $attendancesGrouped->get($dateStr, collect());
                            @endphp
                            <div class="bg-white rounded-2xl border border-gray-100 hover:border-green-200 hover:shadow-md transition-all p-4 sm:p-5">
                                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                    {{-- Kiri: Tanggal & Badge Kegiatan --}}
                                    <div class="flex items-center gap-3.5 min-w-0">
                                        <div class="w-1.5 h-12 bg-gradient-to-b from-green-500 to-emerald-700 rounded-full shrink-0 hidden sm:block"></div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-sm sm:text-base">
                                                {{ \Carbon\Carbon::parse($dateStr)->translatedFormat('l, d F Y') }}
                                            </p>
                                            <div class="flex items-center gap-1.5 mt-1">
                                                @foreach($dayAttendances as $att)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold {{ $att->type === 'harian' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }}">
                                                        {{ $att->type === 'harian' ? 'Harian' : 'Luar Harian' }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Kanan: Histori Jam Check-in & Check-out --}}
                                    <div class="flex flex-col gap-2 lg:ml-auto">
                                        @foreach($dayAttendances as $att)
                                            @php $isHarian = $att->type === 'harian'; @endphp
                                            <div class="flex items-center gap-4 bg-gray-50/80 border border-gray-100 rounded-xl px-4 py-2 text-xs">
                                                <span class="font-bold text-[11px] w-24 shrink-0 {{ $isHarian ? 'text-green-700' : 'text-purple-700' }}">
                                                    {{ $isHarian ? 'Harian' : 'Luar Harian' }}
                                                </span>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-[10px] font-bold text-gray-400 uppercase">In:</span>
                                                    <span class="font-extrabold {{ $isHarian ? 'text-green-700' : 'text-purple-700' }}">
                                                        {{ $att->check_in_time ? \Carbon\Carbon::parse($att->check_in_time)->format('H:i') : '--:--' }}
                                                    </span>
                                                    @if($att->check_in_photo)
                                                        <a href="{{ route('attendances.photo', ['attendance' => $att->id, 'type' => 'check_in']) }}"
                                                           @click.prevent="activePhotoUrl = '{{ route('attendances.photo', ['attendance' => $att->id, 'type' => 'check_in']) }}'; activePhotoTitle = 'Foto Check-In ({{ $isHarian ? 'Harian' : 'Luar Harian' }})'; showPhotoModal = true"
                                                           target="_blank"
                                                           class="text-[11px] text-blue-600 hover:underline font-semibold ml-0.5 cursor-pointer">Foto</a>
                                                    @endif
                                                </div>
                                                <span class="text-gray-300">|</span>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Out:</span>
                                                    <span class="font-extrabold text-red-500">
                                                        {{ $att->check_out_time ? \Carbon\Carbon::parse($att->check_out_time)->format('H:i') : '--:--' }}
                                                    </span>
                                                    @if($att->check_out_photo)
                                                        <a href="{{ route('attendances.photo', ['attendance' => $att->id, 'type' => 'check_out']) }}"
                                                           @click.prevent="activePhotoUrl = '{{ route('attendances.photo', ['attendance' => $att->id, 'type' => 'check_out']) }}'; activePhotoTitle = 'Foto Check-Out ({{ $isHarian ? 'Harian' : 'Luar Harian' }})'; showPhotoModal = true"
                                                           target="_blank"
                                                           class="text-[11px] text-blue-600 hover:underline font-semibold ml-0.5 cursor-pointer">Foto</a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-100 text-gray-300 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <h3 class="text-base font-bold text-gray-500 mb-1">Riwayat Kosong</h3>
                                <p class="text-sm text-gray-400">Belum ada riwayat absensi yang tercatat.</p>
                            </div>
                        @endforelse
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-4">
                            {{ $dates->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Modal Preview Foto Absensi --}}
        <div x-show="showPhotoModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             style="display: none;"
             @keydown.escape.window="showPhotoModal = false">
            
            <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-gray-100"
                 @click.away="showPhotoModal = false">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-green-600 to-emerald-700 text-white">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <h3 class="font-extrabold text-sm" x-text="activePhotoTitle"></h3>
                    </div>
                    <button type="button" @click="showPhotoModal = false" class="text-white/80 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-4 bg-gray-900 flex items-center justify-center min-h-[250px] max-h-[65vh] overflow-hidden">
                    <img :src="activePhotoUrl" alt="Foto Absensi" class="max-h-[60vh] max-w-full rounded-xl object-contain shadow-lg">
                </div>
                <div class="px-6 py-3 bg-gray-50 flex items-center justify-between border-t border-gray-100">
                    <a :href="activePhotoUrl" target="_blank" class="text-xs text-blue-600 hover:underline font-semibold flex items-center gap-1">
                        Buka Ukuran Asli ↗
                    </a>
                    <button type="button" @click="showPhotoModal = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-xl text-xs font-bold text-gray-700 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const video  = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');

        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => { video.srcObject = stream; })
                .catch(() => console.log("Webcam tidak tersedia."));
        }

        function takeSnapshotAndSubmit(formId, photoInputId) {
            if (!video.videoWidth || !video.videoHeight) {
                alert("Kamera belum aktif. Pastikan izin kamera telah diberikan.");
                return;
            }
            const ctx = canvas.getContext('2d');
            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            document.getElementById(photoInputId).value = canvas.toDataURL('image/jpeg');
            document.getElementById(formId).submit();
        }
    </script>
</x-app-layout>
