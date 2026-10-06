<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Absensi Harian
        </h2>
    </x-slot>

    <div class="page-wrapper">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            {{-- ── Form Card ───────────────────────────────────────────────── --}}
            <div class="lg:col-span-1 lg:sticky top-24">
                <div class="glass-effect rounded-3xl overflow-hidden shadow-md">
                    {{-- gradient header --}}
                    <div class="relative overflow-hidden px-6 py-6"
                         style="background: linear-gradient(135deg,#16a34a 0%,#166534 100%)">
                        <div class="absolute -top-8 -right-8 w-28 h-28 rounded-full bg-white/10"></div>
                        <div class="flex items-center gap-3 relative z-10">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-extrabold text-white">Form Absensi</h2>
                                <p class="text-green-200 text-xs mt-0.5">Foto & lokasi otomatis terdeteksi</p>
                            </div>
                        </div>
                    </div>

                    {{-- body --}}
                    <div class="p-6 bg-white/90">
                        {{-- webcam --}}
                        <div class="mb-5">
                            <div class="w-full rounded-2xl overflow-hidden border-2 border-gray-100 bg-gray-900 shadow-inner aspect-video">
                                <video id="webcam" autoplay playsinline class="w-full h-full object-cover"></video>
                            </div>
                            <canvas id="canvas" class="hidden"></canvas>
                            <div class="mt-3 flex items-center justify-center gap-2 text-xs font-semibold text-gray-500 bg-gray-50 border border-gray-100 rounded-xl px-4 py-2">
                                <svg class="w-3.5 h-3.5 text-green-500 animate-pulse shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span id="location-status">Mencari lokasi...</span>
                            </div>
                        </div>

                        {{-- form states --}}
                        @if(!$todayAttendance)
                            <form method="POST" action="{{ route('attendances.checkin') }}" id="checkin-form" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="type" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Tipe Absensi</label>
                                    <select id="type" name="type"
                                        class="w-full border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-2.5 text-sm font-medium transition-all outline-none">
                                        <option value="harian">Absensi Harian</option>
                                        <option value="luar_harian">Kegiatan di Luar Harian</option>
                                    </select>
                                </div>
                                <input type="hidden" name="check_in_photo" id="check_in_photo">
                                <input type="hidden" name="check_in_location" id="check_in_location">
                                <button type="button"
                                    onclick="takeSnapshotAndSubmit('checkin-form','check_in_photo')"
                                    class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm text-white shadow-md shadow-green-200 transition-all"
                                    style="background:linear-gradient(135deg,#22c55e,#16a34a)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Check In Sekarang
                                </button>
                            </form>

                        @elseif(!$todayAttendance->check_out_time)
                            <div class="mb-5 flex items-start gap-3 p-4 bg-green-50 border border-green-200 rounded-2xl">
                                <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm text-green-800 font-medium">
                                    Check-in <span class="font-extrabold">{{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('H:i') }} WIB</span> berhasil dicatat.
                                </p>
                            </div>
                            <form method="POST" action="{{ route('attendances.checkout') }}" id="checkout-form">
                                @csrf
                                <input type="hidden" name="check_out_photo" id="check_out_photo">
                                <input type="hidden" name="check_out_location" id="check_out_location">
                                <button type="button"
                                    onclick="takeSnapshotAndSubmit('checkout-form','check_out_photo')"
                                    class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm text-white shadow-md shadow-red-200 transition-all"
                                    style="background:linear-gradient(135deg,#f87171,#dc2626)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Check Out Sekarang
                                </button>
                            </form>

                        @else
                            <div class="text-center py-6">
                                <div class="mx-auto w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center text-green-600 mb-4 shadow-sm">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <h3 class="text-lg font-extrabold text-gray-900 mb-1">Absensi Selesai!</h3>
                                <p class="text-sm text-gray-500">Check-in &amp; Check-out sudah tercatat hari ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── History Card ─────────────────────────────────────────────── --}}
            <div class="lg:col-span-2">
                <div class="glass-effect rounded-3xl overflow-hidden shadow-md flex flex-col">
                    {{-- header --}}
                    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100/70 bg-white/80">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <h2 class="text-base font-extrabold text-gray-900">Riwayat Absensi</h2>
                        </div>
                    </div>

                    {{-- list --}}
                    <div class="p-5 flex-1 bg-gray-50/40">
                        <div class="space-y-3">
                        @forelse($attendances as $attendance)
                            <div class="bg-white rounded-2xl border border-gray-100 hover:border-green-200 hover:shadow-md transition-all overflow-hidden">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 sm:p-5">
                                    {{-- left: date + type --}}
                                    <div class="flex items-center gap-4 flex-1 min-w-0">
                                        <div class="w-1.5 h-12 bg-gradient-to-b from-green-400 to-green-600 rounded-full shrink-0 hidden sm:block"></div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-900 text-sm truncate">{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('l, d F Y') }}</p>
                                            <span class="badge bg-emerald-100 text-emerald-800 capitalize mt-1">{{ str_replace('_', ' ', $attendance->type) }}</span>
                                        </div>
                                    </div>
                                    {{-- right: times + photos --}}
                                    <div class="flex gap-5 bg-gray-50 border border-gray-100 rounded-xl px-5 py-3 sm:ml-auto">
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Check In</p>
                                            @if($attendance->check_in_time)
                                                <p class="text-sm font-extrabold text-green-600">{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') }}</p>
                                                <a href="{{ Storage::url($attendance->check_in_photo) }}" target="_blank"
                                                   class="text-[11px] text-blue-500 hover:text-blue-700 font-semibold hover:underline">Foto</a>
                                            @else
                                                <p class="text-sm font-extrabold text-gray-300">--:--</p>
                                            @endif
                                        </div>
                                        <div class="w-px bg-gray-200 self-stretch"></div>
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Check Out</p>
                                            @if($attendance->check_out_time)
                                                <p class="text-sm font-extrabold text-red-500">{{ \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') }}</p>
                                                <a href="{{ Storage::url($attendance->check_out_photo) }}" target="_blank"
                                                   class="text-[11px] text-blue-500 hover:text-blue-700 font-semibold hover:underline">Foto</a>
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
                        {{ $attendances->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        const locationStatus = document.getElementById('location-status');
        let currentLocation = '';

        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => { video.srcObject = stream; })
                .catch(() => console.log("Webcam tidak tersedia."));
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                currentLocation = position.coords.latitude + "," + position.coords.longitude;
                locationStatus.innerText = "Lokasi terdeteksi ✓";
                locationStatus.closest('div').classList.add('text-green-600');
                if(document.getElementById('check_in_location'))  document.getElementById('check_in_location').value  = currentLocation;
                if(document.getElementById('check_out_location')) document.getElementById('check_out_location').value = currentLocation;
            }, function() {
                locationStatus.innerText = "Gagal mendapatkan lokasi — izinkan di browser.";
                locationStatus.closest('div').classList.add('text-red-500');
            });
        }

        function takeSnapshotAndSubmit(formId, photoInputId) {
            if(!currentLocation) {
                alert("Sistem masih menunggu deteksi lokasi. Pastikan Anda mengizinkan akses lokasi di browser.");
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
