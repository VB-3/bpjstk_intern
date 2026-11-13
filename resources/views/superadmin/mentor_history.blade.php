<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Histori Capaian Mentor (ARK)
            </h2>
            <a href="{{ route('superadmin.dashboard') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
                Monitoring Live
            </a>
        </div>
    </x-slot>

    <div class="page-wrapper">

        {{-- ── Alert: migrasi belum dijalankan ─────────────────────────── --}}
        @if(!$dbMigrated)
            <div class="flex items-start gap-3 p-4 rounded-2xl bg-amber-50 border border-amber-200 shadow-sm">
                <span class="text-xl shrink-0 mt-0.5">⚠️</span>
                <div>
                    <p class="font-bold text-sm text-amber-900">Tabel database histori belum di-migrasi</p>
                    <p class="text-xs text-amber-800 mt-0.5">
                        Data live dari spreadsheet tetap tampil, namun snapshot harian belum tersimpan permanen.
                        Jalankan <code class="px-1.5 py-0.5 rounded bg-amber-200/70 font-mono font-bold">php artisan migrate --force</code> di terminal SSH.
                    </p>
                </div>
            </div>
        @endif

        {{-- ════════════════════════════════════════════════════════════════
             SECTION 1 — Filter Kalender + Status Bar
             Dibagi 2 bagian: bar utama (judul + date picker) dan sub-bar (status + quick nav)
        ════════════════════════════════════════════════════════════════ --}}
        <div class="rounded-2xl shadow-md border border-green-800/30 overflow-hidden"
             style="background: linear-gradient(135deg, #15803d 0%, #166534 55%, #14532d 100%);">

            {{-- Baris utama: judul kiri, date picker kanan --}}
            <div class="relative p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                {{-- dekorasi lingkaran --}}
                <div class="pointer-events-none absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/10 blur-xl"></div>
                <div class="pointer-events-none absolute -bottom-8 left-16 w-28 h-28 rounded-full bg-yellow-300/10 blur-lg"></div>

                {{-- Judul --}}
                <div class="relative z-10 flex items-center gap-3.5">
                    <div class="shrink-0 w-11 h-11 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center text-yellow-300 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight">Riwayat Rekapitulasi Capaian Mentor</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-400/20 text-yellow-300 border border-yellow-300/30 leading-none">
                                Arsip Harian
                            </span>
                        </div>
                        <p class="text-green-100/90 text-xs mt-0.5">Periksa kondisi data pada tanggal tertentu menggunakan kalender.</p>
                    </div>
                </div>

                {{-- Date Picker Kalender & Tombol Lihat Data (Tampilan Jelas & Pasti Terlihat) --}}
                <div class="relative z-10 w-full lg:w-auto shrink-0">
                    <form method="GET" action="{{ route('superadmin.mentor-history.index') }}"
                          class="flex flex-wrap sm:flex-nowrap items-center gap-2 bg-black/25 backdrop-blur-md p-1.5 rounded-2xl border border-white/20 shadow-inner">
                        <div class="flex items-center gap-1.5 pl-2 text-white">
                            <svg class="w-4 h-4 text-yellow-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs font-bold text-yellow-300 uppercase tracking-wider hidden sm:inline">Pilih:</span>
                        </div>
                        <input type="date"
                               id="date-picker"
                               name="date"
                               value="{{ $selectedDate }}"
                               max="{{ now()->toDateString() }}"
                               onchange="this.form.submit()"
                               class="bg-white text-gray-900 font-bold px-3 py-2 rounded-xl text-xs sm:text-sm border-0 focus:ring-2 focus:ring-yellow-400 shadow cursor-pointer min-w-[150px] flex-1 sm:flex-initial">
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-yellow-400 hover:bg-yellow-300 active:scale-95 text-green-950 font-extrabold text-xs sm:text-sm rounded-xl shadow-md border border-yellow-200 transition-all shrink-0 cursor-pointer">
                            <svg class="w-4 h-4 text-green-950 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Lihat Data</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Sub-bar: status snapshot + navigasi cepat tanggal --}}
            <div class="border-t border-white/10 bg-black/20 px-5 sm:px-6 py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-green-100">

                {{-- Status kiri --}}
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                    <span class="flex items-center gap-1.5 font-semibold text-white">
                        <span class="w-1.5 h-1.5 rounded-full inline-block {{ $snapshot ? 'bg-yellow-400' : 'bg-green-400' }}"></span>
                        {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}
                    </span>

                    @if($snapshot)
                        @if($snapshot->has_changed)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-400/20 text-emerald-200 border border-emerald-400/25">
                                📈 Ada Pembaruan
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-white/10 text-white/70 border border-white/15">
                                Data Stabil
                            </span>
                        @endif
                    @endif

                    <span class="text-green-200/80">
                        @if($isFallbackDate && $snapshot)
                            <span class="text-yellow-300/90 font-medium">Fallback: snapshot {{ $snapshot->snapshot_date->translatedFormat('d M Y') }}</span>
                        @elseif($snapshot)
                            Disimpan {{ $snapshot->updated_at->format('H:i') }} WIB &mdash; {{ $snapshot->header_date ?? '-' }}
                        @else
                            Data live spreadsheet (belum ada snapshot)
                        @endif
                    </span>
                </div>

                {{-- Quick nav kanan --}}
                @if($snapshotsList->isNotEmpty())
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5 shrink-0">
                        <span class="text-[10px] font-bold text-yellow-300/80 uppercase tracking-wider mr-1 shrink-0">Tanggal:</span>
                        @foreach($snapshotsList->take(7) as $snap)
                            <a href="{{ route('superadmin.mentor-history.index', ['date' => $snap->snapshot_date->toDateString()]) }}"
                               class="shrink-0 px-2.5 py-1 rounded-lg font-bold transition-colors
                                      {{ $selectedDate === $snap->snapshot_date->toDateString()
                                          ? 'bg-yellow-400 text-green-950'
                                          : 'bg-white/10 text-white hover:bg-white/20 border border-white/10' }}">
                                {{ $snap->snapshot_date->format('d/m') }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        {{-- /SECTION 1 --}}


        {{-- ════════════════════════════════════════════════════════════════
             SECTION 2 — Konten Data
        ════════════════════════════════════════════════════════════════ --}}
        @if(!empty($mentorPerformance->categories))
            @php
                $catPu    = $mentorPerformance->categories['tk_pu_aktif'] ?? null;
                $catBpu   = $mentorPerformance->categories['bpu_aktif']   ?? null;
                $catIuran = $mentorPerformance->categories['iuran']       ?? null;
            @endphp

            {{-- ════════════════════════════════════════════════════════════
                 CARD NOTIFIKASI PEMBARUAN SPREADSHEET (DI ATAS RINGKASAN CAPAIAN)
            ════════════════════════════════════════════════════════════ --}}
            @include('superadmin.partials.mentor_notification_card')

            {{-- Stat cards --}}
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Ringkasan Capaian</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    {{-- Total Mentor --}}
                    <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                        <div class="stat-icon bg-purple-50 text-purple-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Mentor</p>
                            <p class="text-3xl font-extrabold text-gray-900 mt-0.5">{{ $totalMentors }}</p>
                        </div>
                    </div>

                    {{-- TK PU AKTIF --}}
                    <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                        <div class="stat-icon bg-green-50 text-green-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">TK PU Aktif</p>
                            <div class="flex items-baseline gap-1.5 mt-0.5">
                                <p class="text-3xl font-extrabold text-gray-900">{{ $catPu['total']->realisasi ?? '–' }}</p>
                                <span class="text-sm font-bold text-green-600">{{ $catPu['total']->percent ?? '' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- TK BPU AKTIF --}}
                    <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                        <div class="stat-icon bg-amber-50 text-amber-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">TK BPU Aktif</p>
                            <div class="flex items-baseline gap-1.5 mt-0.5">
                                <p class="text-3xl font-extrabold text-gray-900">{{ $catBpu['total']->realisasi ?? '–' }}</p>
                                <span class="text-sm font-bold text-amber-500">{{ $catBpu['total']->percent ?? '' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- IURAN --}}
                    <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                        <div class="stat-icon bg-blue-50 text-blue-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Iuran Capaian</p>
                            <div class="flex items-baseline gap-1.5 mt-0.5">
                                <p class="text-3xl font-extrabold text-gray-900">{{ $catIuran['total']->percent ?? '–' }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Tabel Rekapitulasi --}}
            <div class="glass-effect rounded-2xl overflow-hidden shadow" x-data="{ activeTab: 'tk_pu_aktif' }">

                {{-- Card header + tabs --}}
                <div class="bg-white px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="font-extrabold text-gray-900">Rekapitulasi Target &amp; Realisasi Mentor</h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Data arsip tercatat pada:
                            <span class="font-semibold text-gray-600">{{ $mentorPerformance->date }}</span>
                        </p>
                    </div>

                    {{-- Tabs --}}
                    <div class="flex items-center gap-1 p-1 bg-gray-100 rounded-xl self-stretch sm:self-auto overflow-x-auto shrink-0">
                        @foreach($mentorPerformance->categories as $catKey => $cat)
                            <button type="button"
                                    @click="activeTab = '{{ $catKey }}'"
                                    :class="activeTab === '{{ $catKey }}'
                                        ? 'bg-white text-green-800 shadow-sm font-bold'
                                        : 'text-gray-500 hover:text-gray-700'"
                                    class="px-3.5 py-1.5 rounded-lg text-xs whitespace-nowrap transition-all">
                                {{ $cat['title'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Table panels --}}
                <div class="bg-white">
                    @foreach($mentorPerformance->categories as $catKey => $cat)
                        <div x-show="activeTab === '{{ $catKey }}'" x-cloak>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[640px] text-sm text-left">
                                    <thead>
                                        <tr class="bg-gray-50 border-b border-gray-100">
                                            <th class="px-6 py-3 text-center w-12 text-xs font-bold text-gray-400 uppercase tracking-wider">No</th>
                                            <th class="px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Mentor (ARK)</th>
                                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Target</th>
                                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Realisasi</th>
                                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">GAP</th>
                                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">% Capaian</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @forelse($cat['mentors'] as $mentor)
                                            @php
                                                $pct = (float) rtrim(str_replace(['%',','], ['',''], $mentor->percent));
                                                $badge = $pct >= 90
                                                    ? 'bg-green-100 text-green-800'
                                                    : ($pct >= 50 ? 'bg-blue-100 text-blue-800' : 'bg-red-50 text-red-700');
                                            @endphp
                                            <tr class="hover:bg-gray-50/60 transition-colors group">
                                                <td class="px-6 py-4 text-center text-xs text-gray-400 font-semibold">
                                                    {{ $mentor->no }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-green-100 to-emerald-50 text-green-700 flex items-center justify-center font-extrabold text-sm shrink-0 shadow-sm">
                                                            {{ strtoupper(substr($mentor->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <p class="font-semibold text-gray-900 leading-tight">{{ $mentor->name }}</p>
                                                            <p class="text-xs text-gray-400">Account Representative Khusus</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-right text-gray-500">{{ $mentor->target }}</td>
                                                <td class="px-6 py-4 text-right font-bold text-gray-900">{{ $mentor->realisasi }}</td>
                                                <td class="px-6 py-4 text-right font-semibold
                                                    {{ str_contains($mentor->gap, '-') || str_contains($mentor->gap, '(') ? 'text-red-500' : 'text-green-600' }}">
                                                    {{ $mentor->gap }}
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $badge }}">
                                                        {{ $mentor->percent }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="py-14 text-center text-gray-400 text-sm">
                                                    Tidak ada data mentor pada kategori ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                    @if(!empty($cat['total']))
                                        <tfoot>
                                            <tr class="bg-gray-50 border-t-2 border-gray-200 font-bold">
                                                <td colspan="2" class="px-6 py-4 text-xs uppercase tracking-wider text-gray-600">
                                                    Total {{ $cat['title'] }}
                                                </td>
                                                <td class="px-6 py-4 text-right text-gray-600">{{ $cat['total']->target }}</td>
                                                <td class="px-6 py-4 text-right text-gray-900 font-extrabold">{{ $cat['total']->realisasi }}</td>
                                                <td class="px-6 py-4 text-right font-bold
                                                    {{ str_contains($cat['total']->gap, '-') || str_contains($cat['total']->gap, '(') ? 'text-red-500' : 'text-green-600' }}">
                                                    {{ $cat['total']->gap }}
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-green-700 text-white">
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
            {{-- Empty State --}}
            <div class="glass-effect rounded-2xl py-20 text-center shadow">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-100 text-3xl mb-4">📅</div>
                <p class="font-bold text-gray-800 text-base">Belum Ada Catatan Histori</p>
                <p class="text-gray-500 text-sm mt-1 max-w-sm mx-auto">
                    Data histori akan tersimpan otomatis setiap 24 jam saat spreadsheet terhubung.
                </p>
                <a href="{{ route('superadmin.dashboard') }}"
                   class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors">
                    Buka Monitoring Live
                </a>
            </div>
        @endif

    </div>{{-- /page-wrapper --}}
</x-app-layout>
