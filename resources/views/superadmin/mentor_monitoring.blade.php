<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Dashboard Monitoring Mentor
            </h2>
            <div class="flex items-center gap-2">
                <a href="https://docs.google.com/spreadsheets/d/1TyRm64Onlmpyfqzw3MyICPGpttH-3_rTYOZHFS94iWY/edit?usp=sharing"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-3.5 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-xs shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Buka Spreadsheet
                </a>
            </div>
        </div>
    </x-slot>

    <div class="page-wrapper">

        {{-- ── Hero Welcome Card (Standar Hijau BPJS) ─────────────────────── --}}
        <div class="relative overflow-hidden rounded-3xl shadow-lg mb-6"
             style="background: linear-gradient(135deg, #15803d 0%, #166534 50%, #14532d 100%);">
            <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-white/10 pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>

            <div class="relative z-10 p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center text-white shadow-lg shrink-0 text-2xl font-extrabold overflow-hidden">
                        <svg class="w-8 h-8 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-400/20 text-yellow-300 border border-yellow-300/30">Super Administrator</span>
                            <span class="text-xs text-green-200">• Realtime Spreadsheet</span>
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-white mt-1">Pemantauan Progres Mentor (ARK)</h3>
                        <p class="text-green-100/80 text-sm mt-0.5">
                            Laporan realisasi target dan capaian {{ $mentorPerformance->date ?? 'terbaru' }} dari para mentor pembimbing.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Stat Cards Row: Ringkasan Capaian ──────────────────────────── --}}
        @php
            $catPu = $mentorPerformance->categories['tk_pu_aktif'] ?? null;
            $catBpu = $mentorPerformance->categories['bpu_aktif'] ?? null;
            $catIuran = $mentorPerformance->categories['iuran'] ?? null;
        @endphp
        <div class="mb-6">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Statistik Capaian</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                {{-- Total Mentor --}}
                <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                    <div class="stat-icon bg-purple-50 text-purple-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Total Mentor</p>
                        <p class="text-3xl font-extrabold text-gray-900 mt-0.5">{{ $totalMentors }}</p>
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

        {{-- ── Tabel Progres Mentor (Gaya Standar Putih / Card BPJS) ───────── --}}
        <div class="glass-effect rounded-3xl overflow-hidden shadow-md mb-6" x-data="{ activeTab: 'tk_pu_aktif' }">
            {{-- Header card & Tab navigation --}}
            <div class="px-6 sm:px-8 py-5 border-b border-gray-100 bg-white flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-extrabold text-gray-900">Rekapitulasi Target & Realisasi Mentor</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Data terhubung langsung dengan Google Spreadsheet secara real-time.</p>
                </div>

                {{-- Tabs biasa sederhana --}}
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
                                                Tidak ada data mentor.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($cat['total'])
                                    <tfoot class="bg-gray-50/90 font-bold border-t border-gray-200 text-gray-800">
                                        <tr>
                                            <td colspan="2" class="px-6 sm:px-8 py-4 uppercase text-xs tracking-wider text-gray-700">
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

        {{-- ── Akun Mentor Terdaftar (Gaya Standar Sederhana) ─────────────── --}}
        <div class="glass-effect rounded-3xl overflow-hidden shadow-md">
            <div class="px-6 sm:px-8 py-5 border-b border-gray-100 bg-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-extrabold text-gray-900">Daftar Akun Mentor di Sistem</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Mentor yang aktif membimbing peserta magang.</p>
                </div>
                <div class="text-xs font-semibold text-gray-500 bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-xl">
                    Total: <span class="font-extrabold text-gray-800">{{ $mentorsList->count() }}</span> mentor
                </div>
            </div>

            <div class="overflow-x-auto bg-white">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100 text-gray-500">
                            <th class="px-6 sm:px-8 py-4 font-bold text-xs uppercase tracking-wider">Nama Mentor</th>
                            <th class="px-6 sm:px-8 py-4 font-bold text-xs uppercase tracking-wider">Email</th>
                            <th class="px-6 sm:px-8 py-4 font-bold text-xs uppercase tracking-wider text-center">Anak Didik</th>
                            <th class="px-6 sm:px-8 py-4 font-bold text-xs uppercase tracking-wider text-center">Tugas Dibuat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($mentorsList as $m)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 sm:px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($m->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $m->name }}</p>
                                            <p class="text-xs text-gray-400">Account Representative Khusus</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 sm:px-8 py-4 text-gray-600 font-medium">
                                    {{ $m->email }}
                                </td>
                                <td class="px-6 sm:px-8 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        {{ $m->mentees_count ?? 0 }} Peserta
                                    </span>
                                </td>
                                <td class="px-6 sm:px-8 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                        {{ $m->assigned_tasks_count ?? 0 }} Tugas
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-10 text-center text-gray-400">
                                    Belum ada akun mentor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
