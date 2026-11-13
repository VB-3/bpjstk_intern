<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-bold text-lg sm:text-xl text-gray-800 leading-tight flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-green-100 text-green-700 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span>Histori Capaian Mentor (ARK)</span>
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('superadmin.dashboard') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    <span>Monitoring Live</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="page-wrapper space-y-5">

        {{-- ── Alert jika migrasi belum dijalankan ───────────────────────── --}}
        @if(!$dbMigrated)
            <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-3 shadow-sm">
                <span class="text-lg shrink-0">⚠️</span>
                <div class="text-xs">
                    <p class="font-bold text-amber-950">Tabel database histori belum di-migrasi</p>
                    <p class="mt-0.5 text-amber-800">
                        Saat ini data live dari spreadsheet tetap ditampilkan, namun snapshot histori harian belum bisa disimpan permanen.
                        Silakan jalankan perintah <code class="px-1.5 py-0.5 rounded bg-amber-200/60 font-mono font-bold text-amber-900">php artisan migrate --force</code> di terminal SSH Dewacloud kamu.
                    </p>
                </div>
            </div>
        @endif

        {{-- ── Hero & Kalender Filter Section (Desain Kompak & Proporsional) ────────────────────── --}}
        <div class="relative overflow-hidden rounded-2xl shadow-md border border-green-800/20"
             style="background: linear-gradient(135deg, #15803d 0%, #166534 50%, #14532d 100%);">
            {{-- Subtle Ambient Glow --}}
            <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-white/10 blur-xl pointer-events-none"></div>
            <div class="absolute -bottom-8 -left-8 w-36 h-36 rounded-full bg-yellow-400/10 blur-lg pointer-events-none"></div>

            <div class="relative z-10 p-4 sm:p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4 text-white">
                <div class="flex items-start sm:items-center gap-3">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-white/15 backdrop-blur-md border border-white/20 flex items-center justify-center text-yellow-300 shadow-inner shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-base sm:text-lg font-extrabold text-white tracking-tight">Riwayat Rekapitulasi Capaian Mentor</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-400/20 text-yellow-300 border border-yellow-300/30">
                                Arsip Harian
                            </span>
                        </div>
                        <p class="text-xs text-green-100/90 mt-0.5 leading-snug max-w-xl">
                            Merekam progres berkala target dan realisasi mentor. Gunakan kalender untuk memeriksa kondisi data pada tanggal tertentu.
                        </p>
                    </div>
                </div>

                {{-- Date Picker Kalender Kompak --}}
                <div class="w-full lg:w-auto shrink-0">
                    <form method="GET" action="{{ route('superadmin.mentor-history.index') }}" class="flex items-center gap-2 bg-black/20 backdrop-blur-md p-1.5 rounded-xl border border-white/15 shadow-inner">
                        <div class="flex items-center gap-1.5 pl-2">
                            <svg class="w-4 h-4 text-yellow-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-[11px] font-bold text-yellow-300 uppercase tracking-wider hidden sm:inline">Pilih:</span>
                        </div>
                        <input type="date"
                               id="date-picker"
                               name="date"
                               value="{{ $selectedDate }}"
                               max="{{ now()->toDateString() }}"
                               onchange="this.form.submit()"
                               class="w-full sm:w-40 bg-white text-gray-800 font-bold px-3 py-1.5 rounded-lg text-xs border-0 focus:ring-2 focus:ring-yellow-400 shadow-sm outline-none cursor-pointer">
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-1 px-3 py-1.5 bg-yellow-400 hover:bg-yellow-300 text-green-950 font-extrabold rounded-lg text-xs shadow transition-colors shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Buka</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- ── Sub-bar: Status Snapshot & Navigasi Cepat Tanggal ── --}}
            <div class="relative z-10 bg-black/15 border-t border-white/10 px-4 sm:px-5 py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 text-xs text-green-100">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="font-bold text-white flex items-center gap-1.5">
                        <span class="inline-block w-2 h-2 rounded-full {{ $snapshot ? 'bg-yellow-400' : 'bg-green-400' }}"></span>
                        {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}
                    </span>
                    @if($snapshot)
                        @if($snapshot->has_changed)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-400/25 text-emerald-200 border border-emerald-400/30">
                                Ada Pembaruan Data
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-white/10 text-white/80 border border-white/15">
                                Data Stabil
                            </span>
                        @endif
                    @endif

                    <span class="text-white/40 hidden md:inline">•</span>

                    <span class="text-[11px] text-green-200/90">
                        @if($isFallbackDate && $snapshot)
                            <span class="text-yellow-300 font-semibold">Snapshot terakhir per 24 jam tercatat pada {{ $snapshot->snapshot_date->translatedFormat('d M Y') }}</span>
                        @elseif($snapshot)
                            Diperbarui: {{ $snapshot->updated_at->format('H:i') }} WIB ({{ $snapshot->header_date ?? '-' }})
                        @else
                            Menampilkan data live terbaru spreadsheet
                        @endif
                    </span>
                </div>

                {{-- Quick Nav Tanggal Tersedia --}}
                @if($snapshotsList->isNotEmpty())
                    <div class="flex items-center gap-1.5 overflow-x-auto max-w-full pb-0.5 sm:pb-0 shrink-0">
                        <span class="text-[10px] font-bold text-yellow-300/80 uppercase tracking-wider shrink-0 mr-1">Histori:</span>
                        @foreach($snapshotsList->take(6) as $snap)
                            <a href="{{ route('superadmin.mentor-history.index', ['date' => $snap->snapshot_date->toDateString()]) }}"
                               class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all shrink-0
                                      {{ $selectedDate === $snap->snapshot_date->toDateString()
                                          ? 'bg-yellow-400 text-green-950 shadow-sm'
                                          : 'bg-white/15 text-white hover:bg-white/25 border border-white/10' }}">
                                {{ $snap->snapshot_date->format('d/m') }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        @if(!empty($mentorPerformance->categories))
            @php
                $catPu = $mentorPerformance->categories['tk_pu_aktif'] ?? null;
                $catBpu = $mentorPerformance->categories['bpu_aktif'] ?? null;
                $catIuran = $mentorPerformance->categories['iuran'] ?? null;
            @endphp

            {{-- ── Stat Cards Ringkasan ──────────────────────────────────────── --}}
            <div>
                <div class="flex items-center justify-between mb-2.5">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Ringkasan Capaian pada Tanggal Ini</h3>
                    <span class="text-xs text-gray-400">Total ARK: <strong class="text-gray-700">{{ $totalMentors }}</strong></span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Total Mentor --}}
                    <div class="glass-effect rounded-2xl p-4 card-hover flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">TOTAL MENTOR (ARK)</p>
                            <p class="text-2xl font-extrabold text-gray-900 mt-0.5">{{ $totalMentors }}</p>
                        </div>
                    </div>

                    {{-- Realisasi TK PU AKTIF --}}
                    <div class="glass-effect rounded-2xl p-4 card-hover flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">TK PU AKTIF</p>
                            <div class="flex items-baseline gap-1 mt-0.5">
                                <p class="text-2xl font-extrabold text-gray-900">{{ $catPu['total']->realisasi ?? '-' }}</p>
                                <span class="text-xs font-bold text-green-600">({{ $catPu['total']->percent ?? '' }})</span>
                            </div>
                        </div>
                    </div>

                    {{-- Realisasi TK BPU AKTIF --}}
                    <div class="glass-effect rounded-2xl p-4 card-hover flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">TK BPU AKTIF</p>
                            <div class="flex items-baseline gap-1 mt-0.5">
                                <p class="text-2xl font-extrabold text-gray-900">{{ $catBpu['total']->realisasi ?? '-' }}</p>
                                <span class="text-xs font-bold text-amber-600">({{ $catBpu['total']->percent ?? '' }})</span>
                            </div>
                        </div>
                    </div>

                    {{-- Capaian IURAN --}}
                    <div class="glass-effect rounded-2xl p-4 card-hover flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">IURAN</p>
                            <div class="flex items-baseline gap-1 mt-0.5">
                                <p class="text-2xl font-extrabold text-gray-900">{{ $catIuran['total']->percent ?? '-' }}</p>
                                <span class="text-xs text-gray-400 font-semibold">capaian</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Tabel Rekapitulasi Data Snapshot ──────────────────────────── --}}
            <div class="glass-effect rounded-2xl overflow-hidden shadow-md" x-data="{ activeTab: 'tk_pu_aktif' }">
                {{-- Header card & Tab navigation --}}
                <div class="px-5 sm:px-6 py-4 border-b border-gray-100 bg-white flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm sm:text-base font-extrabold text-gray-900">Rekapitulasi Target & Realisasi Mentor</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Arsip data per 24 jam tercatat pada: <strong class="text-gray-700">{{ $mentorPerformance->date }}</strong>.</p>
                    </div>

                    {{-- Tabs --}}
                    <div class="flex items-center gap-1 p-1 bg-gray-100 rounded-xl self-stretch md:self-auto overflow-x-auto">
                        @foreach($mentorPerformance->categories as $catKey => $cat)
                            <button type="button"
                                    @click="activeTab = '{{ $catKey }}'"
                                    :class="activeTab === '{{ $catKey }}' ? 'bg-white text-green-800 shadow-sm font-bold' : 'text-gray-500 hover:text-gray-700 font-medium'"
                                    class="px-3.5 py-1.5 rounded-lg text-xs whitespace-nowrap transition-all">
                                {{ $cat['title'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Table body --}}
                <div class="bg-white">
                    @foreach($mentorPerformance->categories as $catKey => $cat)
                        <div x-show="activeTab === '{{ $catKey }}'" style="display: none;">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[720px] text-left text-sm">
                                    <thead>
                                        <tr class="bg-gray-50/80 border-b border-gray-100 text-gray-500">
                                            <th class="px-5 sm:px-6 py-3.5 text-center w-14 font-bold text-xs uppercase tracking-wider">No</th>
                                            <th class="px-5 sm:px-6 py-3.5 font-bold text-xs uppercase tracking-wider">Nama Mentor (ARK)</th>
                                            <th class="px-5 sm:px-6 py-3.5 font-bold text-xs uppercase tracking-wider text-right">Target</th>
                                            <th class="px-5 sm:px-6 py-3.5 font-bold text-xs uppercase tracking-wider text-right">Realisasi</th>
                                            <th class="px-5 sm:px-6 py-3.5 font-bold text-xs uppercase tracking-wider text-right">GAP</th>
                                            <th class="px-5 sm:px-6 py-3.5 font-bold text-xs uppercase tracking-wider text-center">% Capaian</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @forelse($cat['mentors'] as $mentor)
                                            @php
                                                $percentNum = (float) rtrim(str_replace(['%', ','], '', $mentor->percent), ' ');
                                                $badgeClass = $percentNum >= 90 ? 'bg-green-100 text-green-800' : ($percentNum >= 50 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700');
                                            @endphp
                                            <tr class="hover:bg-gray-50/50 transition-colors">
                                                <td class="px-5 sm:px-6 py-3.5 text-center font-semibold text-gray-400 text-xs">
                                                    {{ $mentor->no }}
                                                </td>
                                                <td class="px-5 sm:px-6 py-3.5">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-lg bg-green-100 text-green-700 flex items-center justify-center font-extrabold text-xs shrink-0">
                                                            {{ strtoupper(substr($mentor->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <p class="font-bold text-gray-900 text-xs sm:text-sm">{{ $mentor->name }}</p>
                                                            <p class="text-[11px] text-gray-400">Account Representative Khusus</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-5 sm:px-6 py-3.5 text-right text-gray-600 font-medium text-xs sm:text-sm">
                                                    {{ $mentor->target }}
                                                </td>
                                                <td class="px-5 sm:px-6 py-3.5 text-right font-bold text-gray-900 text-xs sm:text-sm">
                                                    {{ $mentor->realisasi }}
                                                </td>
                                                <td class="px-5 sm:px-6 py-3.5 text-right font-semibold text-xs sm:text-sm {{ str_contains($mentor->gap, '-') || str_contains($mentor->gap, '(') ? 'text-red-500' : 'text-green-600' }}">
                                                    {{ $mentor->gap }}
                                                </td>
                                                <td class="px-5 sm:px-6 py-3.5 text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $badgeClass }}">
                                                        {{ $mentor->percent }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                                    Tidak ada data mentor pada kategori ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if(!empty($cat['total']))
                                        <tfoot class="bg-gray-50 border-t-2 border-gray-100 font-bold text-gray-800">
                                            <tr>
                                                <td colspan="2" class="px-5 sm:px-6 py-3.5 uppercase text-xs tracking-wider">
                                                    Total {{ $cat['title'] }}
                                                </td>
                                                <td class="px-5 sm:px-6 py-3.5 text-right text-gray-700 text-xs sm:text-sm">
                                                    {{ $cat['total']->target }}
                                                </td>
                                                <td class="px-5 sm:px-6 py-3.5 text-right text-gray-900 font-extrabold text-xs sm:text-sm">
                                                    {{ $cat['total']->realisasi }}
                                                </td>
                                                <td class="px-5 sm:px-6 py-3.5 text-right text-xs sm:text-sm {{ str_contains($cat['total']->gap, '-') || str_contains($cat['total']->gap, '(') ? 'text-red-500' : 'text-green-600' }}">
                                                    {{ $cat['total']->gap }}
                                                </td>
                                                <td class="px-5 sm:px-6 py-3.5 text-center">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-green-600 text-white">
                                                        {{ $cat['total']->percent }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            {{-- Empty State jika belum ada data sama sekali --}}
            <div class="glass-effect rounded-2xl p-12 text-center shadow-md border border-white/60">
                <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-3 text-2xl">
                    📅
                </div>
                <p class="text-gray-800 font-bold text-base">Belum Ada Catatan Histori</p>
                <p class="text-gray-500 text-xs mt-1 max-w-md mx-auto">
                    Data histori per 24 jam akan otomatis tersimpan begitu data Google Spreadsheet terhubung. Silakan buka halaman monitoring live terlebih dahulu.
                </p>
                <a href="{{ route('superadmin.dashboard') }}"
                   class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
                    Buka Monitoring Live Sekarang
                </a>
            </div>
        @endif

    </div>
</x-app-layout>
