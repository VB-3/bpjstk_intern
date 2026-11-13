<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Absensi
        </h2>
    </x-slot>

    <div class="page-wrapper" x-data="webcamApp()">

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
                        <span class="text-xs text-gray-400 font-medium">{{ $attendances->total() }} total catatan</span>
                    </div>

                    <div class="p-5 flex-1 bg-gray-50/40">
                        <div class="space-y-3">
                        @forelse($attendances as $att)
                            @php
                                $isHarian = $att->type === 'harian';
                                $colorIn  = $isHarian ? 'text-green-600' : 'text-purple-600';
                                $bgBadge  = $isHarian ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800';
                                $barColor = $isHarian ? 'from-green-400 to-green-600' : 'from-purple-400 to-purple-600';
                            @endphp
                            <div class="bg-white rounded-2xl border border-gray-100 hover:border-green-200 hover:shadow-md transition-all overflow-hidden">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 sm:p-5">
                                    {{-- Left: tanggal + tipe --}}
                                    <div class="flex items-center gap-4 flex-1 min-w-0">
                                        <div class="w-1.5 h-12 bg-gradient-to-b {{ $barColor }} rounded-full shrink-0 hidden sm:block"></div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-900 text-sm truncate">
                                                {{ \Carbon\Carbon::parse($att->date)->translatedFormat('l, d F Y') }}
                                            </p>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold mt-1 {{ $bgBadge }}">
                                                {{ $isHarian ? 'Harian' : 'Luar Harian' }}
                                            </span>
                                        </div>
                                    </div>
                                    {{-- Right: waktu check in/out --}}
                                    <div class="flex gap-5 bg-gray-50 border border-gray-100 rounded-xl px-5 py-3 sm:ml-auto shrink-0">
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Check In</p>
                                            @if($att->check_in_time)
                                                <p class="text-sm font-extrabold {{ $colorIn }}">
                                                    {{ \Carbon\Carbon::parse($att->check_in_time)->format('H:i') }}
                                                </p>
                                                <a href="{{ Storage::url($att->check_in_photo) }}" target="_blank"
                                                   class="text-[11px] text-blue-500 hover:underline font-semibold">Foto</a>
                                            @else
                                                <p class="text-sm font-extrabold text-gray-300">--:--</p>
                                            @endif
                                        </div>
                                        <div class="w-px bg-gray-200 self-stretch"></div>
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Check Out</p>
                                            @if($att->check_out_time)
                                                <p class="text-sm font-extrabold text-red-500">
                                                    {{ \Carbon\Carbon::parse($att->check_out_time)->format('H:i') }}
                                                </p>
                                                <a href="{{ Storage::url($att->check_out_photo) }}" target="_blank"
                                                   class="text-[11px] text-blue-500 hover:underline font-semibold">Foto</a>
                                            @else
                                                <p class="text-sm font-extrabold text-gray-300">--:--</p>
                                            @endif
                                        </div>
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
                            {{ $attendances->links('vendor.pagination.custom') }}
                        </div>
                    </div>
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
