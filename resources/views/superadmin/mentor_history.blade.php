<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Histori Capaian Mentor (ARK)
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('superadmin.dashboard') }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    Kembali ke Monitoring Live
                </a>
            </div>
        </div>
    </x-slot>

    <div class="page-wrapper space-y-6">

        {{-- ── Hero & Kalender Filter Section ────────────────────────────── --}}
        <div class="relative overflow-hidden rounded-3xl shadow-lg"
             style="background: linear-gradient(135deg, #15803d 0%, #166534 50%, #14532d 100%);">
            <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-white/10 pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>

            <div class="relative z-10 p-6 sm:p-8 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 text-white">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-400/20 text-yellow-300 border border-yellow-300/30">
                            Arsip & Histori Harian
                        </span>
                        <span class="text-xs text-green-200">• Snapshot Terakhir per 24 Jam</span>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white mt-2">Riwayat Rekapitulasi Capaian Mentor</h3>
                    <p class="text-green-100/90 text-sm mt-1 max-w-xl">
                        Merekam progres berkala target dan realisasi mentor. Gunakan kalender untuk memeriksa kondisi data pada tanggal tertentu.
                    </p>
                </div>

                {{-- Date Picker Kalender --}}
                <div class="w-full lg:w-auto bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl shadow-inner shrink-0">
                    <form method="GET" action="{{ route('superadmin.mentor-history.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3">
                        <div>
                            <label for="date-picker" class="block text-[11px] font-bold text-yellow-300 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Pilih Tanggal Kalender
                            </label>
                            <input type="date"
                                   id="date-picker"
                                   name="date"
                                   value="{{ $selectedDate }}"
                                   max="{{ now()->toDateString() }}"
                                   onchange="this.form.submit()"
                                   class="w-full sm:w-48 bg-white text-gray-800 font-bold px-3.5 py-2 rounded-xl text-sm border-0 focus:ring-2 focus:ring-yellow-400 shadow-sm outline-none cursor-pointer">
                        </div>
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-yellow-400 hover:bg-yellow-300 text-green-950 font-extrabold rounded-xl text-sm shadow-md transition-colors">
                            Buka
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── Status Info Banner ────────────────────────────────────────── --}}
        <div class="glass-effect rounded-2xl p-4 sm:p-5 shadow-sm border border-white/60 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl {{ $snapshot && $snapshot->has_changed ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }} flex items-center justify-center font-bold text-lg shrink-0">
                    {{ $snapshot && $snapshot->has_changed ? '📈' : '📋' }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-extrabold text-gray-900">
                            Data untuk tanggal: <span class="text-green-700">{{ \Carbon\Carbon::parse($selectedDate)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                        </p>
                        @if($snapshot)
                            @if($snapshot->has_changed)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Ada Pembaruan Data
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                    Data Stabil (Sama dng sebelumnya)
                                </span>
                            @endif
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        @if($isFallbackDate && $snapshot)
                            <span class="text-amber-600 font-semibold">ℹ️ Pada tanggal ini tidak ada catatan baru, menampilkan data snapshot terakhir tercatat pada {{ $snapshot->snapshot_date->locale('id')->isoFormat('D MMMM YYYY') }}.</span>
                        @elseif($snapshot)
                            Snapshot terakhir per 24 jam diperbarui pada: <strong>{{ $snapshot->updated_at->format('H:i:s, d/m/Y') }}</strong> ({{ $snapshot->header_date ?? '-' }}).
                        @else
                            Belum ada snapshot histori yang tersimpan.
                        @endif
                    </p>
                </div>
            </div>

            {{-- Quick Nav Tanggal Tersedia --}}
            @if($snapshotsList->isNotEmpty())
                <div class="flex items-center gap-1.5 overflow-x-auto max-w-full pb-1 self-stretch md:self-auto">
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider shrink-0 mr-1">Histori:</span>
                    @foreach($snapshotsList->take(6) as $snap)
                        <a href="{{ route('superadmin.mentor-history.index', ['date' => $snap->snapshot_date->toDateString()]) }}"
                           class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all shrink-0
                                  {{ $selectedDate === $snap->snapshot_date->toDateString()
                                      ? 'bg-green-700 text-white shadow-sm'
                                      : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            {{ $snap->snapshot_date->format('d/m') }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        @if(!empty($mentorPerformance->categories))
            @php
                $catPu = $mentorPerformance->categories['tk_pu_aktif'] ?? null;
                $catBpu = $mentorPerformance->categories['bpu_aktif'] ?? null;
                $catIuran = $mentorPerformance->categories['iuran'] ?? null;
            @endphp

            {{-- ── Stat Cards Ringkasan ──────────────────────────────────────── --}}
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Ringkasan Capaian pada Tanggal Ini</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    {{-- Total Mentor --}}
                    <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                        <div class="stat-icon bg-purple-50 text-purple-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">TOTAL MENTOR (ARK)</p>
                            <p class="text-2xl font-extrabold text-gray-900 mt-0.5">{{ $totalMentors }}</p>
                        </div>
                    </div>

                    {{-- Realisasi TK PU AKTIF --}}
                    <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                        <div class="stat-icon bg-green-50 text-green-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                        <div class="stat-icon bg-amber-50 text-amber-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                        <div class="stat-icon bg-blue-50 text-blue-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">IURAN</p>
                            <div class="flex items-baseline gap-1 mt-0.5">
                                <p class="text-2xl font-extrabold text-gray-900">{{ $catIuran['total']->percent ?? '-' }}</p>
                                <span class="text-xs text-gray-400">capaian</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Tabel Rekapitulasi Data Snapshot ──────────────────────────── --}}
            <div class="glass-effect rounded-3xl overflow-hidden shadow-md" x-data="{ activeTab: 'tk_pu_aktif' }">
                {{-- Header card & Tab navigation --}}
                <div class="px-6 sm:px-8 py-5 border-b border-gray-100 bg-white flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900">Rekapitulasi Target & Realisasi Mentor</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Arsip data per 24 jam tercatat pada: {{ $mentorPerformance->date }}.</p>
                    </div>

                    {{-- Tabs --}}
                    <div class="flex items-center gap-1 p-1 bg-gray-100 rounded-xl self-stretch md:self-auto overflow-x-auto">
                        @foreach($mentorPerformance->categories as $catKey => $cat)
                            <button type="button"
                                    @click="activeTab = '{{ $catKey }}'"
                                    :class="activeTab === '{{ $catKey }}' ? 'bg-white text-gray-900 shadow-sm font-bold' : 'text-gray-500 hover:text-gray-700 font-medium'"
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
                                            <th class="px-6 sm:px-8 py-3.5 text-center w-14 font-bold text-xs uppercase tracking-wider">No</th>
                                            <th class="px-6 sm:px-8 py-3.5 font-bold text-xs uppercase tracking-wider">Nama Mentor (ARK)</th>
                                            <th class="px-6 sm:px-8 py-3.5 font-bold text-xs uppercase tracking-wider text-right">Target</th>
                                            <th class="px-6 sm:px-8 py-3.5 font-bold text-xs uppercase tracking-wider text-right">Realisasi</th>
                                            <th class="px-6 sm:px-8 py-3.5 font-bold text-xs uppercase tracking-wider text-right">GAP</th>
                                            <th class="px-6 sm:px-8 py-3.5 font-bold text-xs uppercase tracking-wider text-center">% Capaian</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @forelse($cat['mentors'] as $mentor)
                                            @php
                                                $percentNum = (float) rtrim(str_replace(['%', ','], '', $mentor->percent), ' ');
                                                $badgeClass = $percentNum >= 90 ? 'bg-green-100 text-green-800' : ($percentNum >= 50 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700');
                                            @endphp
                                            <tr class="hover:bg-gray-50/50 transition-colors">
                                                <td class="px-6 sm:px-8 py-4 text-center font-semibold text-gray-400 text-xs">
                                                    {{ $mentor->no }}
                                                </td>
                                                <td class="px-6 sm:px-8 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-9 h-9 rounded-xl bg-green-100 text-green-700 flex items-center justify-center font-extrabold text-xs shrink-0">
                                                            {{ strtoupper(substr($mentor->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <p class="font-bold text-gray-900">{{ $mentor->name }}</p>
                                                            <p class="text-xs text-gray-400">Account Representative Khusus</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 sm:px-8 py-4 text-right text-gray-600 font-medium">
                                                    {{ $mentor->target }}
                                                </td>
                                                <td class="px-6 sm:px-8 py-4 text-right font-bold text-gray-900">
                                                    {{ $mentor->realisasi }}
                                                </td>
                                                <td class="px-6 sm:px-8 py-4 text-right font-medium {{ str_contains($mentor->gap, '-') || str_contains($mentor->gap, '(') ? 'text-red-500' : 'text-green-600' }}">
                                                    {{ $mentor->gap }}
                                                </td>
                                                <td class="px-6 sm:px-8 py-4 text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $badgeClass }}">
                                                        {{ $mentor->percent }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-8 py-10 text-center text-gray-400">
                                                    Tidak ada data mentor pada kategori ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if(!empty($cat['total']))
                                        <tfoot class="bg-gray-50 border-t-2 border-gray-100 font-bold text-gray-800">
                                            <tr>
                                                <td colspan="2" class="px-6 sm:px-8 py-4 uppercase text-xs tracking-wider">
                                                    Total {{ $cat['title'] }}
                                                </td>
                                                <td class="px-6 sm:px-8 py-4 text-right text-gray-700">
                                                    {{ $cat['total']->target }}
                                                </td>
                                                <td class="px-6 sm:px-8 py-4 text-right text-gray-900 font-extrabold">
                                                    {{ $cat['total']->realisasi }}
                                                </td>
                                                <td class="px-6 sm:px-8 py-4 text-right {{ str_contains($cat['total']->gap, '-') || str_contains($cat['total']->gap, '(') ? 'text-red-500' : 'text-green-600' }}">
                                                    {{ $cat['total']->gap }}
                                                </td>
                                                <td class="px-6 sm:px-8 py-4 text-center">
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
            <div class="glass-effect rounded-3xl p-16 text-center shadow-md border border-white/60">
                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4 text-3xl">
                    📅
                </div>
                <p class="text-gray-700 font-bold text-base">Belum Ada Catatan Histori</p>
                <p class="text-gray-400 text-sm mt-1 max-w-md mx-auto">
                    Data histori per 24 jam akan otomatis tersimpan begitu data Google Spreadsheet terhubung. Silakan buka halaman monitoring live terlebih dahulu.
                </p>
                <a href="{{ route('superadmin.dashboard') }}"
                   class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors">
                    Buka Monitoring Live Sekarang
                </a>
            </div>
        @endif

    </div>
</x-app-layout>
