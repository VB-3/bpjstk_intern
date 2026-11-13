<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Galeri Foto Absensi
            </h2>
            <div class="text-xs font-semibold text-gray-500 bg-gray-100/80 px-3 py-1.5 rounded-xl border border-gray-200">
                {{ count($galleryData) }} peserta &middot;
                @if($filterMonth)
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $filterMonth)->locale('id')->isoFormat('MMMM YYYY') }}
                @endif
            </div>
        </div>
    </x-slot>

    {{-- Lightbox --}}
    <div id="lightbox"
         class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center hidden"
         onclick="closeLightbox()">
        <button onclick="closeLightbox()"
                class="absolute top-5 right-5 w-9 h-9 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="max-w-lg w-full mx-6" onclick="event.stopPropagation()">
            <img id="lightbox-img" src="" alt=""
                 class="w-full rounded-2xl shadow-2xl max-h-[75vh] object-contain">
            <p id="lightbox-caption"
               class="mt-3 text-center text-white/90 text-sm font-semibold"></p>
        </div>
    </div>

    <div class="page-wrapper space-y-5">

        {{-- ── Filter ──────────────────────────────────────── --}}
        <div class="glass-effect rounded-3xl p-6 shadow-md border border-white/60">
            <form method="GET" action="{{ route('superadmin.attendance-gallery.index') }}"
                  class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">

                {{-- Filter Bulan --}}
                <div class="sm:col-span-4">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                        Periode Bulan
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <select name="month"
                                class="w-full pl-9 pr-4 py-2.5 border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl text-sm font-medium transition-all outline-none">
                            @foreach($availableMonths as $month)
                                <option value="{{ $month }}" {{ $filterMonth === $month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('id')->isoFormat('MMMM YYYY') }}
                                </option>
                            @endforeach
                            @if($availableMonths->isEmpty())
                                <option value="{{ now()->format('Y-m') }}">
                                    {{ now()->locale('id')->isoFormat('MMMM YYYY') }}
                                </option>
                            @endif
                        </select>
                    </div>
                </div>

                {{-- Filter Peserta --}}
                <div class="sm:col-span-5">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                        Peserta Magang
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <select name="intern_id"
                                class="w-full pl-9 pr-4 py-2.5 border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl text-sm font-medium transition-all outline-none">
                            <option value="">Semua Peserta</option>
                            @foreach($interns as $intern)
                                <option value="{{ $intern->id }}" {{ request('intern_id') == $intern->id ? 'selected' : '' }}>
                                    {{ $intern->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="sm:col-span-3 flex items-center gap-2">
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm text-white shadow-md shadow-green-200 hover:shadow-lg transition-all"
                            style="background: linear-gradient(135deg, #16a34a, #15803d);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                    </button>
                    @if(request()->filled('month') || request()->filled('intern_id'))
                        <a href="{{ route('superadmin.attendance-gallery.index') }}"
                           class="px-3.5 py-2.5 bg-gray-100 hover:bg-red-50 hover:text-red-600 text-gray-500 rounded-xl text-sm font-semibold border border-gray-200 transition-colors"
                           title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ── Daftar Peserta + Foto ─────────────────────────── --}}
        @forelse($galleryData as $data)
        @php
            $intern     = $data['intern'];
            $attendances = $data['attendances'];
        @endphp

        <div class="glass-effect rounded-3xl overflow-hidden shadow-md border border-white/60">

            {{-- Header Peserta --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100/70 bg-white/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-sm text-white shadow-sm shrink-0"
                         style="background: linear-gradient(135deg, #16a34a, #15803d);">
                        {{ strtoupper(mb_substr($intern->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 text-sm">{{ $intern->name }}</p>
                        <p class="text-xs text-gray-400 font-medium">NIM: {{ $intern->nim ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-50 border border-green-200 text-green-700 text-xs font-bold">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $data['total_foto'] }} Sesi
                    </span>
                    <a href="{{ route('superadmin.attendances.index', ['user_id' => $intern->id]) }}"
                       class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold transition-colors border border-gray-200">
                        Riwayat
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Baris foto scroll horizontal --}}
            <div class="p-5 bg-white/50 overflow-x-auto">
                <div class="flex gap-4" style="min-width: max-content;">

                    @foreach($attendances as $attendance)
                    @php
                        $hasIn      = !empty($attendance->check_in_photo);
                        $hasOut     = !empty($attendance->check_out_photo);
                        $urlIn      = $hasIn  ? route('attendances.photo', ['attendance' => $attendance->id, 'type' => 'check_in'])  : null;
                        $urlOut     = $hasOut ? route('attendances.photo', ['attendance' => $attendance->id, 'type' => 'check_out']) : null;
                        $tgl        = $attendance->date ? $attendance->date->locale('id')->isoFormat('ddd, D MMM') : '-';
                        $isHarian   = $attendance->type === 'harian';
                    @endphp

                    <div class="flex flex-col gap-2.5 shrink-0 w-[160px]">

                        {{-- Label tanggal --}}
                        <div class="text-center px-1">
                            <p class="text-xs font-extrabold text-gray-800">{{ $tgl }}</p>
                            <span class="inline-block mt-1 text-[10px] font-bold px-2.5 py-0.5 rounded-full
                                {{ $isHarian ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                                {{ $isHarian ? 'Harian' : 'Luar Harian' }}
                            </span>
                        </div>

                        {{-- Check In --}}
                        <div class="relative group">
                            @if($hasIn)
                                <img src="{{ $urlIn }}"
                                     alt="Check In"
                                     loading="lazy"
                                     onclick="openLightbox('{{ $urlIn }}', 'Check-In: {{ addslashes($intern->name) }} — {{ $tgl }}')"
                                     class="w-full h-32 object-cover rounded-2xl shadow-sm border border-gray-200 cursor-pointer
                                            transition duration-200 group-hover:shadow-md group-hover:border-green-400 group-hover:scale-[1.02]">
                                <div class="absolute inset-0 rounded-2xl bg-black/0 group-hover:bg-black/5 transition duration-200 pointer-events-none"></div>
                                <span class="absolute bottom-2 left-2 bg-green-600/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-lg shadow-sm">
                                    CHECK IN
                                </span>
                                @if($attendance->check_in_time)
                                <span class="absolute top-2 right-2 bg-black/50 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-lg">
                                    {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') }}
                                </span>
                                @endif
                            @else
                                <div class="w-full h-32 rounded-2xl bg-gray-50 border border-dashed border-gray-200 flex flex-col items-center justify-center gap-1">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-[10px] text-gray-400 font-semibold text-center leading-tight">Tidak ada<br>foto check-in</span>
                                </div>
                            @endif
                        </div>

                        {{-- Check Out --}}
                        <div class="relative group">
                            @if($hasOut)
                                <img src="{{ $urlOut }}"
                                     alt="Check Out"
                                     loading="lazy"
                                     onclick="openLightbox('{{ $urlOut }}', 'Check-Out: {{ addslashes($intern->name) }} — {{ $tgl }}')"
                                     class="w-full h-32 object-cover rounded-2xl shadow-sm border border-gray-200 cursor-pointer
                                            transition duration-200 group-hover:shadow-md group-hover:border-red-400 group-hover:scale-[1.02]">
                                <div class="absolute inset-0 rounded-2xl bg-black/0 group-hover:bg-black/5 transition duration-200 pointer-events-none"></div>
                                <span class="absolute bottom-2 left-2 bg-red-500/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-lg shadow-sm">
                                    CHECK OUT
                                </span>
                                @if($attendance->check_out_time)
                                <span class="absolute top-2 right-2 bg-black/50 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-lg">
                                    {{ \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') }}
                                </span>
                                @endif
                            @else
                                <div class="w-full h-32 rounded-2xl bg-gray-50 border border-dashed border-gray-200 flex flex-col items-center justify-center gap-1">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-[10px] text-gray-400 font-semibold text-center leading-tight">Tidak ada<br>foto check-out</span>
                                </div>
                            @endif
                        </div>

                    </div>
                    @endforeach

                </div>
            </div>

        </div>
        @empty

        {{-- Empty State --}}
        <div class="glass-effect rounded-3xl p-16 text-center shadow-md border border-white/60">
            <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-gray-700 font-bold text-base">Belum ada foto absensi</p>
            <p class="text-gray-400 text-sm mt-1">
                Tidak ada foto absensi untuk periode
                @if($filterMonth)
                    <strong class="text-gray-600">{{ \Carbon\Carbon::createFromFormat('Y-m', $filterMonth)->locale('id')->isoFormat('MMMM YYYY') }}</strong>.
                @else
                    yang dipilih.
                @endif
            </p>
            <a href="{{ route('superadmin.attendance-gallery.index') }}"
               class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors">
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
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
    </script>

</x-app-layout>
