<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Monitoring Kinerja & Progress Mentor (ARK)
            </h2>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Live Google Sheets
                </span>
            </div>
        </div>
    </x-slot>

    <div class="page-wrapper space-y-6">

        {{-- ── Hero Welcome Card ────────────────────────────────────────── --}}
        <div class="relative overflow-hidden rounded-3xl shadow-lg"
             style="background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%);">
            <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-white/10 pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>

            <div class="relative z-10 p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center text-white shadow-lg shrink-0 text-3xl font-extrabold overflow-hidden">
                        👔
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-400/20 text-yellow-300 border border-yellow-300/30">
                                Super Admin — Monitoring Mentor
                            </span>
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-white mt-1">Panel Pantau Kinerja Mentor (ARK)</h3>
                        <p class="text-green-100/90 text-sm mt-0.5">
                            Data realisasi target, capaian iuran, dan jumlah binaan dari para pembimbing / Account Representative Khusus (ARK).
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <a href="https://docs.google.com/spreadsheets/d/1TyRm64Onlmpyfqzw3MyICPGpttH-3_rTYOZHFS94iWY/edit?usp=sharing"
                       target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-emerald-800 hover:bg-emerald-50 font-bold rounded-xl text-sm shadow-md transition-all">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Buka Spreadsheet
                    </a>
                </div>
            </div>
        </div>

        {{-- ── Stat Cards Overview ───────────────────────────────────────── --}}
        <div>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Ringkasan Mentor & Pembimbing</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                {{-- Total Mentor --}}
                <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                    <div class="stat-icon bg-purple-50 text-purple-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Total Mentor (ARK)</p>
                        <p class="text-3xl font-extrabold text-gray-900 mt-0.5">{{ $totalMentors }}</p>
                    </div>
                </div>

                {{-- Realisasi TK PU --}}
                @php
                    $catPu = $mentorPerformance->categories['tk_pu_aktif'] ?? null;
                    $catBpu = $mentorPerformance->categories['bpu_aktif'] ?? null;
                    $catIuran = $mentorPerformance->categories['iuran'] ?? null;
                @endphp
                <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                    <div class="stat-icon bg-emerald-50 text-emerald-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Realisasi TK PU AKTIF</p>
                        <p class="text-2xl font-extrabold text-gray-900 mt-0.5">{{ $catPu['total']->realisasi ?? '-' }}</p>
                        <span class="text-[10px] font-bold text-emerald-600">{{ $catPu['total']->percent ?? '' }} dari target</span>
                    </div>
                </div>

                {{-- Realisasi TK BPU --}}
                <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                    <div class="stat-icon bg-amber-50 text-amber-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Realisasi TK BPU AKTIF</p>
                        <p class="text-2xl font-extrabold text-gray-900 mt-0.5">{{ $catBpu['total']->realisasi ?? '-' }}</p>
                        <span class="text-[10px] font-bold text-amber-600">{{ $catBpu['total']->percent ?? '' }} dari target</span>
                    </div>
                </div>

                {{-- Realisasi IURAN --}}
                <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                    <div class="stat-icon bg-blue-50 text-blue-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Capaian IURAN</p>
                        <p class="text-2xl font-extrabold text-gray-900 mt-0.5">{{ $catIuran['total']->percent ?? '-' }}</p>
                        <span class="text-[10px] font-bold text-blue-600 truncate block max-w-[170px]" title="Rp {{ $catIuran['total']->realisasi ?? '' }}">
                            Rp {{ $catIuran['total']->realisasi ?? '' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Realtime Table Tabs ───────────────────────────────────────── --}}
        <div class="glass-effect rounded-3xl overflow-hidden shadow-lg border border-white/50" x-data="{ activeTab: 'tk_pu_aktif' }">
            <div class="bg-gradient-to-r from-emerald-800 to-green-700 p-6 sm:px-8 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-2xl shrink-0 shadow-inner">
                        📊
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-xl font-extrabold tracking-tight">Rincian Realisasi Tiap Mentor</h3>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-white/20 text-yellow-300 border border-yellow-300/30">
                                <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                                Real Time
                            </span>
                        </div>
                        <p class="text-xs text-green-100/90 mt-1">
                            Laporan Spreadsheet: <span class="font-bold text-yellow-200">{{ $mentorPerformance->date }}</span>
                        </p>
                    </div>
                </div>

                {{-- Tab Buttons --}}
                <div class="flex items-center gap-1.5 p-1.5 bg-black/20 rounded-2xl border border-white/10 self-stretch md:self-auto overflow-x-auto">
                    @foreach($mentorPerformance->categories as $catKey => $cat)
                        <button type="button"
                                @click="activeTab = '{{ $catKey }}'"
                                :class="activeTab === '{{ $catKey }}' ? 'bg-white text-emerald-900 shadow-md font-extrabold' : 'text-green-100 hover:text-white hover:bg-white/10 font-semibold'"
                                class="px-4 py-2 rounded-xl text-xs whitespace-nowrap transition-all duration-200">
                            {{ $cat['title'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white/70">
                @foreach($mentorPerformance->categories as $catKey => $cat)
                    <div x-show="activeTab === '{{ $catKey }}'" style="display: none;" class="space-y-6">
                        {{-- Top KPI Cards per category --}}
                        @if($cat['total'])
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Target Akumulasi</p>
                                    <p class="text-xl sm:text-2xl font-extrabold text-gray-800 mt-1">{{ $cat['total']->target }}</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">{{ $cat['unit'] }}</p>
                                </div>
                                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100">
                                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Realisasi Akumulasi</p>
                                    <p class="text-xl sm:text-2xl font-extrabold text-emerald-700 mt-1">{{ $cat['total']->realisasi }}</p>
                                    <p class="text-[11px] text-emerald-600/70 mt-0.5">{{ $cat['unit'] }}</p>
                                </div>
                                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100">
                                    <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest">GAP Selisih</p>
                                    <p class="text-xl sm:text-2xl font-extrabold text-amber-700 mt-1">{{ $cat['total']->gap }}</p>
                                    <p class="text-[11px] text-amber-600/70 mt-0.5">Selisih Target</p>
                                </div>
                                <div class="p-4 rounded-2xl bg-blue-50 border border-blue-100">
                                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Persentase Rata-rata</p>
                                    <p class="text-xl sm:text-2xl font-extrabold text-blue-700 mt-1">{{ $cat['total']->percent }}</p>
                                    <p class="text-[11px] text-blue-600/70 mt-0.5">Capaian Keseluruhan</p>
                                </div>
                            </div>
                        @endif

                        {{-- Table of mentors --}}
                        <div class="overflow-x-auto rounded-2xl border border-gray-100 shadow-sm bg-white">
                            <table class="w-full min-w-[720px] text-left text-sm">
                                <thead class="bg-gray-50/80 text-gray-500 font-bold border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-3.5 text-center w-14 text-xs uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3.5 text-xs uppercase tracking-wider">Nama Mentor (ARK)</th>
                                        <th class="px-6 py-3.5 text-right text-xs uppercase tracking-wider">Target</th>
                                        <th class="px-6 py-3.5 text-right text-xs uppercase tracking-wider">Realisasi</th>
                                        <th class="px-6 py-3.5 text-right text-xs uppercase tracking-wider">GAP</th>
                                        <th class="px-6 py-3.5 text-center text-xs uppercase tracking-wider">% Capaian</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @forelse($cat['mentors'] as $mentor)
                                        @php
                                            $percentNum = (float) rtrim(str_replace(['%', ','], '', $mentor->percent), ' ');
                                            $badgeColor = $percentNum >= 95 ? 'bg-emerald-100 text-emerald-800' : ($percentNum >= 80 ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800');
                                        @endphp
                                        <tr class="hover:bg-emerald-50/30 transition-colors">
                                            <td class="px-6 py-4 text-center font-bold text-gray-400 text-xs">
                                                {{ $mentor->no }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 text-white flex items-center justify-center font-extrabold text-xs shadow-sm">
                                                        {{ strtoupper(substr($mentor->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-gray-900">{{ $mentor->name }}</p>
                                                        <span class="inline-block text-[10px] text-gray-400 font-medium">Mentor / Pembimbing</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-right font-semibold text-gray-600">
                                                {{ $mentor->target }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-extrabold text-emerald-700">
                                                {{ $mentor->realisasi }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-semibold {{ str_contains($mentor->gap, '-') || str_contains($mentor->gap, '(') ? 'text-rose-600' : 'text-emerald-600' }}">
                                                {{ $mentor->gap }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black {{ $badgeColor }}">
                                                    {{ $mentor->percent }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                                Tidak ada data mentor.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($cat['total'])
                                    <tfoot class="bg-emerald-50/50 font-extrabold border-t-2 border-emerald-200/60 text-gray-800">
                                        <tr>
                                            <td colspan="2" class="px-6 py-4 uppercase text-xs tracking-wider text-emerald-900">
                                                Total Keseluruhan {{ $cat['title'] }}
                                            </td>
                                            <td class="px-6 py-4 text-right text-gray-700">
                                                {{ $cat['total']->target }}
                                            </td>
                                            <td class="px-6 py-4 text-right text-emerald-800">
                                                {{ $cat['total']->realisasi }}
                                            </td>
                                            <td class="px-6 py-4 text-right {{ str_contains($cat['total']->gap, '-') || str_contains($cat['total']->gap, '(') ? 'text-rose-700' : 'text-emerald-700' }}">
                                                {{ $cat['total']->gap }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-black bg-emerald-600 text-white shadow-sm">
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

        {{-- ── Daftar Mentor Lokal Database ─────────────────────────────── --}}
        <div class="glass-effect rounded-3xl overflow-hidden shadow-lg border border-white/50">
            <div class="bg-white p-6 sm:px-8 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl font-extrabold text-gray-900">Akun Mentor Terdaftar</h3>
                    <p class="text-sm text-gray-500 mt-1">Daftar mentor yang memiliki akun dan jumlah peserta bimbingan di portal BPJS.</p>
                </div>
                <div class="text-xs font-semibold text-gray-500 bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-xl">
                    Total: <span class="font-extrabold text-gray-800">{{ $mentorsList->count() }}</span> mentor
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-left text-sm">
                    <thead class="bg-gray-50/80 text-gray-500 font-bold border-b border-gray-100">
                        <tr>
                            <th class="px-8 py-4 font-bold text-xs uppercase tracking-wider">Nama Mentor</th>
                            <th class="px-8 py-4 font-bold text-xs uppercase tracking-wider">Email</th>
                            <th class="px-8 py-4 font-bold text-xs uppercase tracking-wider text-center">Jumlah Anak Didik</th>
                            <th class="px-8 py-4 font-bold text-xs uppercase tracking-wider text-center">Tugas Dibuat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($mentorsList as $m)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-extrabold text-sm shrink-0">
                                            {{ strtoupper(substr($m->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $m->name }}</p>
                                            <p class="text-xs text-gray-400">Pembimbing Lapangan</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4 text-gray-600 font-medium">
                                    {{ $m->email }}
                                </td>
                                <td class="px-8 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        {{ $m->mentees_count ?? 0 }} Peserta
                                    </span>
                                </td>
                                <td class="px-8 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                        {{ $m->assigned_tasks_count ?? 0 }} Tugas
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-10 text-center text-gray-400">
                                    Belum ada akun mentor terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
