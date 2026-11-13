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
                <a href="{{ route('superadmin.mentor-history.index') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-gray-50 text-gray-700 font-semibold border border-gray-200 rounded-xl text-xs transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Histori Capaian
                </a>
                <a href="https://docs.google.com/spreadsheets/d/1TyRm64Onlmpyfqzw3MyICPGpttH-3_rTYOZHFS94iWY/edit?usp=sharing"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl text-xs shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Spreadsheet
                </a>
            </div>
        </div>
    </x-slot>

    <div class="page-wrapper">

        {{-- Hero --}}
        <div class="relative overflow-hidden rounded-2xl shadow"
             style="background: linear-gradient(135deg, #15803d 0%, #166534 55%, #14532d 100%);">
            <div class="pointer-events-none absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/10"></div>
            <div class="pointer-events-none absolute -bottom-8 left-16 w-28 h-28 rounded-full bg-yellow-300/10"></div>

            <div class="relative p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="shrink-0 w-14 h-14 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center text-yellow-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-0.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-400/20 text-yellow-300 border border-yellow-300/30">Super Administrator</span>
                            <span class="text-xs text-green-200/80">Realtime Spreadsheet</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-white">Pemantauan Progres Mentor (ARK)</h3>
                        <p class="text-green-100/80 text-xs mt-0.5">Laporan realisasi target dan capaian {{ $mentorPerformance->date ?? 'terbaru' }} dari para mentor pembimbing.</p>
                    </div>
                </div>
                <a href="{{ route('superadmin.mentor-history.index') }}"
                   class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2.5 bg-yellow-400 hover:bg-yellow-300 text-green-900 font-bold rounded-xl text-sm shadow transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Riwayat Kalender
                </a>
            </div>
        </div>

        {{-- Stat Cards --}}
        @php
            $catPu    = $mentorPerformance->categories['tk_pu_aktif'] ?? null;
            $catBpu   = $mentorPerformance->categories['bpu_aktif']   ?? null;
            $catIuran = $mentorPerformance->categories['iuran']       ?? null;
        @endphp
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Statistik Capaian</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                    <div class="stat-icon bg-purple-50 text-purple-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Mentor</p>
                        <p class="text-3xl font-extrabold text-gray-900 mt-0.5">{{ $totalMentors }}</p>
                    </div>
                </div>

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
            <div class="bg-white px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="font-extrabold text-gray-900">Rekapitulasi Target &amp; Realisasi Mentor</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Data terhubung langsung dengan Google Spreadsheet secara real-time.</p>
                </div>
                <div class="flex items-center gap-1 p-1 bg-gray-100 rounded-xl self-stretch sm:self-auto overflow-x-auto shrink-0">
                    @foreach($mentorPerformance->categories as $catKey => $cat)
                        <button type="button"
                                @click="activeTab = '{{ $catKey }}'"
                                :class="activeTab === '{{ $catKey }}' ? 'bg-white text-green-800 shadow-sm font-bold' : 'text-gray-500 hover:text-gray-700'"
                                class="px-3.5 py-1.5 rounded-lg text-xs whitespace-nowrap transition-all">
                            {{ $cat['title'] }}
                        </button>
                    @endforeach
                </div>
            </div>

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
                                            $badge = $pct >= 90 ? 'bg-green-100 text-green-800' : ($pct >= 50 ? 'bg-blue-100 text-blue-800' : 'bg-red-50 text-red-700');
                                        @endphp
                                        <tr class="hover:bg-gray-50/60 transition-colors">
                                            <td class="px-6 py-4 text-center text-xs text-gray-400 font-semibold">{{ $mentor->no }}</td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-green-100 to-emerald-50 text-green-700 flex items-center justify-center font-extrabold text-sm shrink-0">
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
                                            <td class="px-6 py-4 text-right font-semibold {{ str_contains($mentor->gap, '-') || str_contains($mentor->gap, '(') ? 'text-red-500' : 'text-green-600' }}">
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
                                            <td colspan="6" class="py-14 text-center text-sm text-gray-400">Tidak ada data mentor.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($cat['total'])
                                    <tfoot>
                                        <tr class="bg-gray-50 border-t-2 border-gray-200 font-bold">
                                            <td colspan="2" class="px-6 py-4 text-xs uppercase tracking-wider text-gray-600">Total {{ $cat['title'] }}</td>
                                            <td class="px-6 py-4 text-right text-gray-600">{{ $cat['total']->target }}</td>
                                            <td class="px-6 py-4 text-right text-gray-900 font-extrabold">{{ $cat['total']->realisasi }}</td>
                                            <td class="px-6 py-4 text-right font-bold {{ str_contains($cat['total']->gap, '-') || str_contains($cat['total']->gap, '(') ? 'text-red-500' : 'text-green-600' }}">
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

    </div>{{-- /page-wrapper --}}
</x-app-layout>
