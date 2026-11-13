<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Galeri Foto Absensi Peserta Magang
            </h2>
        </div>
    </x-slot>

    {{-- Lightbox Modal --}}
    <div id="lightbox" class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center hidden" onclick="closeLightbox()">
        <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white bg-white/10 hover:bg-white/20 rounded-full p-2 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="max-w-2xl w-full mx-4" onclick="event.stopPropagation()">
            <img id="lightbox-img" src="" alt="" class="w-full rounded-2xl shadow-2xl max-h-[80vh] object-contain bg-black">
            <div id="lightbox-caption" class="mt-3 text-center text-white text-sm font-semibold"></div>
        </div>
    </div>

    <div class="page-wrapper space-y-6">

        {{-- Filter Bar --}}
        <div class="glass-effect rounded-2xl p-5 shadow border border-white/60">
            <form method="GET" action="{{ route('superadmin.attendance-gallery.index') }}" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Periode Bulan</label>
                    <select name="month" onchange="this.form.submit()"
                        class="w-full border border-gray-200 bg-white rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 focus:border-green-500 focus:ring-green-500 outline-none shadow-sm">
                        @foreach($availableMonths as $month)
                            <option value="{{ $month }}" {{ $filterMonth === $month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('id')->isoFormat('MMMM YYYY') }}
                            </option>
                        @endforeach
                        @if($availableMonths->isEmpty())
                            <option value="{{ now()->format('Y-m') }}">{{ now()->locale('id')->isoFormat('MMMM YYYY') }}</option>
                        @endif
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Filter Peserta</label>
                    <select name="intern_id" onchange="this.form.submit()"
                        class="w-full border border-gray-200 bg-white rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 focus:border-green-500 focus:ring-green-500 outline-none shadow-sm">
                        <option value="">Semua Peserta</option>
                        @foreach($interns as $intern)
                            <option value="{{ $intern->id }}" {{ request('intern_id') == $intern->id ? 'selected' : '' }}>
                                {{ $intern->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <a href="{{ route('superadmin.attendance-gallery.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm font-bold text-gray-600 transition border border-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </a>
            </form>
        </div>

        {{-- Summary --}}
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                Menampilkan
                <span class="text-green-700">{{ count($galleryData) }}</span>
                peserta dengan foto absensi
                @if($filterMonth)
                    — <span class="text-green-700">{{ \Carbon\Carbon::createFromFormat('Y-m', $filterMonth)->locale('id')->isoFormat('MMMM YYYY') }}</span>
                @endif
            </span>
        </div>

        @forelse($galleryData as $data)
        @php
            $intern = $data['intern'];
            $attendances = $data['attendances'];
        @endphp

        {{-- Card Per Peserta --}}
        <div class="glass-effect rounded-3xl overflow-hidden shadow-lg border border-white/60">

            {{-- Header Peserta --}}
            <div class="bg-gradient-to-r from-green-700 to-emerald-600 px-6 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center font-extrabold text-white text-base shadow-inner shrink-0">
                        {{ strtoupper(mb_substr($intern->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-extrabold text-white text-base leading-tight">{{ $intern->name }}</p>
                        <p class="text-green-100 text-xs mt-0.5">NIM: {{ $intern->nim ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/20 text-white text-xs font-bold">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $data['total_foto'] }} Sesi Foto
                    </span>
                    <a href="{{ route('superadmin.attendances.index', ['user_id' => $intern->id]) }}"
                       class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/10 hover:bg-white/25 text-white text-xs font-semibold transition border border-white/20">
                        Lihat Riwayat
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Grid Foto (1 baris scroll horizontal per peserta) --}}
            <div class="p-5 bg-white/80 overflow-x-auto">
                <div class="flex gap-4 pb-2" style="min-width: max-content;">

                    @foreach($attendances as $attendance)
                    @php
                        $hasCheckIn  = !empty($attendance->check_in_photo);
                        $hasCheckOut = !empty($attendance->check_out_photo);
                        $checkInUrl  = $hasCheckIn  ? route('attendances.photo', ['attendance' => $attendance->id, 'type' => 'check_in'])  : null;
                        $checkOutUrl = $hasCheckOut ? route('attendances.photo', ['attendance' => $attendance->id, 'type' => 'check_out']) : null;
                        $dateLabel   = $attendance->date ? $attendance->date->locale('id')->isoFormat('ddd, D MMM') : '-';
                        $typeLabel   = $attendance->type === 'harian' ? 'Harian' : 'Luar Harian';
                        $typeBadge   = $attendance->type === 'harian' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700';
                    @endphp

                    <div class="flex flex-col gap-2 shrink-0 w-[150px]">

                        {{-- Label tanggal --}}
                        <div class="text-center">
                            <p class="text-xs font-extrabold text-gray-700">{{ $dateLabel }}</p>
                            <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-full {{ $typeBadge }} mt-0.5">
                                {{ $typeLabel }}
                            </span>
                        </div>

                        {{-- Foto Check In --}}
                        <div class="relative group">
                            @if($hasCheckIn)
                                <img src="{{ $checkInUrl }}"
                                     alt="Check In {{ $intern->name }}"
                                     loading="lazy"
                                     onclick="openLightbox('{{ $checkInUrl }}', 'Check-In: {{ addslashes($intern->name) }} — {{ $dateLabel }}')"
                                     class="w-full h-28 object-cover rounded-xl border-2 border-emerald-200 shadow cursor-pointer transition group-hover:scale-105 group-hover:shadow-lg group-hover:border-emerald-400">
                                <span class="absolute bottom-1.5 left-1.5 bg-emerald-600/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-lg">
                                    CHECK IN
                                </span>
                                @if($attendance->check_in_time)
                                <span class="absolute top-1.5 right-1.5 bg-black/60 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-lg">
                                    {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') }}
                                </span>
                                @endif
                            @else
                                <div class="w-full h-28 rounded-xl bg-gray-100 border-2 border-dashed border-gray-200 flex items-center justify-center">
                                    <span class="text-[10px] text-gray-400 font-semibold text-center px-2">Tidak ada foto<br>check-in</span>
                                </div>
                            @endif
                        </div>

                        {{-- Foto Check Out --}}
                        <div class="relative group">
                            @if($hasCheckOut)
                                <img src="{{ $checkOutUrl }}"
                                     alt="Check Out {{ $intern->name }}"
                                     loading="lazy"
                                     onclick="openLightbox('{{ $checkOutUrl }}', 'Check-Out: {{ addslashes($intern->name) }} — {{ $dateLabel }}')"
                                     class="w-full h-28 object-cover rounded-xl border-2 border-rose-200 shadow cursor-pointer transition group-hover:scale-105 group-hover:shadow-lg group-hover:border-rose-400">
                                <span class="absolute bottom-1.5 left-1.5 bg-rose-600/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-lg">
                                    CHECK OUT
                                </span>
                                @if($attendance->check_out_time)
                                <span class="absolute top-1.5 right-1.5 bg-black/60 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-lg">
                                    {{ \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') }}
                                </span>
                                @endif
                            @else
                                <div class="w-full h-28 rounded-xl bg-gray-100 border-2 border-dashed border-gray-200 flex items-center justify-center">
                                    <span class="text-[10px] text-gray-400 font-semibold text-center px-2">Tidak ada foto<br>check-out</span>
                                </div>
                            @endif
                        </div>

                    </div>
                    @endforeach

                </div>
            </div>

        </div>
        @empty
        <div class="glass-effect rounded-3xl p-16 text-center shadow border border-white/60">
            <div class="text-5xl mb-4">📷</div>
            <p class="text-gray-500 font-semibold text-lg">Belum ada foto absensi</p>
            <p class="text-gray-400 text-sm mt-1">
                Tidak ada foto absensi peserta magang untuk periode
                @if($filterMonth)
                    <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $filterMonth)->locale('id')->isoFormat('MMMM YYYY') }}</strong>.
                @else
                    yang dipilih.
                @endif
            </p>
            <a href="{{ route('superadmin.attendance-gallery.index') }}"
               class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-bold transition">
                Lihat Semua Periode
            </a>
        </div>
        @endforelse

    </div>

    <script>
        function openLightbox(src, caption) {
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox-caption').textContent = caption;
            document.getElementById('lightbox').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeLightbox() {
            document.getElementById('lightbox').classList.add('hidden');
            document.getElementById('lightbox-img').src = '';
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLightbox();
        });
    </script>
</x-app-layout>
