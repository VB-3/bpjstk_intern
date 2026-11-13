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
                {{-- Tombol Sinkron dari Spreadsheet --}}
                <form method="POST" action="{{ route('superadmin.mentor-performance.sync') }}" class="inline"
                      onsubmit="return confirm('Sinkronkan data dari Google Spreadsheet? Data lokal periode ini akan ditimpa.');">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Sinkron Spreadsheet
                    </button>
                </form>
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

    <div class="page-wrapper" x-data="mentorMonitoring()">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-4 px-5 py-3.5 bg-green-50 border border-green-200 text-green-800 rounded-2xl flex items-center gap-3 text-sm font-medium shadow-sm">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 px-5 py-3.5 bg-red-50 border border-red-200 text-red-800 rounded-2xl flex items-center gap-3 text-sm font-medium shadow-sm">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ── Hero Welcome Card ─────────────────────────────────────────────── --}}
        <div class="relative overflow-hidden rounded-3xl shadow-lg mb-6"
             style="background: linear-gradient(135deg, #15803d 0%, #166534 50%, #14532d 100%);">
            <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-white/10 pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>

            <div class="relative z-10 p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center shadow-lg shrink-0">
                        <svg class="w-8 h-8 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-400/20 text-yellow-300 border border-yellow-300/30">Super Administrator</span>
                            @if(!$useSheet)
                                <span class="text-xs text-green-200">• Data Lokal (Editable)</span>
                            @else
                                <span class="text-xs text-blue-200">• Realtime Spreadsheet</span>
                            @endif
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-white mt-1">Pemantauan Progres Mentor (ARK)</h3>
                        <p class="text-green-100/80 text-sm mt-0.5">
                            Laporan realisasi target dan capaian mentor pembimbing. Kamu dapat mengedit data langsung dari dashboard ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Stat Cards ────────────────────────────────────────────────────── --}}
        @php
            $displayData = $useSheet ? $mentorPerformance->categories : $dbPerformance;
            $catPu  = $displayData['tk_pu_aktif'] ?? null;
            $catBpu = $displayData['bpu_aktif'] ?? null;
            $catIuran = $displayData['iuran'] ?? null;

            // Ambil total dari masing-masing
            $puTotal    = is_array($catPu)  ? ($catPu['total']  ?? null) : ($catPu->total ?? null);
            $bpuTotal   = is_array($catBpu) ? ($catBpu['total'] ?? null) : ($catBpu->total ?? null);
            $iuranTotal = is_array($catIuran) ? ($catIuran['total'] ?? null) : ($catIuran->total ?? null);
        @endphp

        <div class="mb-6">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Statistik Capaian</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                {{-- Total Mentor --}}
                <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                    <div class="stat-icon bg-purple-50 text-purple-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Total Mentor</p>
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
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">TK PU AKTIF</p>
                        <div class="flex items-baseline gap-1 mt-0.5">
                            <p class="text-2xl font-extrabold text-gray-900">{{ $puTotal->realisasi ?? '-' }}</p>
                            <span class="text-xs font-bold text-green-600">({{ $puTotal->percent ?? '' }})</span>
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
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">TK BPU AKTIF</p>
                        <div class="flex items-baseline gap-1 mt-0.5">
                            <p class="text-2xl font-extrabold text-gray-900">{{ $bpuTotal->realisasi ?? '-' }}</p>
                            <span class="text-xs font-bold text-amber-600">({{ $bpuTotal->percent ?? '' }})</span>
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
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">IURAN</p>
                        <div class="flex items-baseline gap-1 mt-0.5">
                            <p class="text-2xl font-extrabold text-gray-900">{{ $iuranTotal->percent ?? '-' }}</p>
                            <span class="text-xs text-gray-400">capaian</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Tabel Rekapitulasi (Editable) ────────────────────────────────── --}}
        <div class="glass-effect rounded-3xl overflow-hidden shadow-md mb-6" x-data="{ activeTab: 'tk_pu_aktif' }">
            {{-- Header + Tab --}}
            <div class="px-6 sm:px-8 py-5 border-b border-gray-100 bg-white flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-extrabold text-gray-900">Rekapitulasi Target & Realisasi Mentor</h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        @if(!$useSheet)
                            Data tersimpan di database lokal — dapat diedit langsung.
                        @else
                            Data dari Google Spreadsheet (read-only). Klik <strong>Sinkron Spreadsheet</strong> untuk mengimpor ke database agar bisa diedit.
                        @endif
                    </p>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    {{-- Tabs --}}
                    <div class="flex items-center gap-1 p-1 bg-gray-100 rounded-xl overflow-x-auto">
                        @foreach(['tk_pu_aktif' => 'TK PU AKTIF', 'bpu_aktif' => 'TK BPU AKTIF', 'iuran' => 'IURAN'] as $catKey => $catLabel)
                            <button type="button"
                                    @click="activeTab = '{{ $catKey }}'"
                                    :class="activeTab === '{{ $catKey }}' ? 'bg-white text-gray-900 shadow-sm font-bold' : 'text-gray-500 hover:text-gray-700 font-medium'"
                                    class="px-3.5 py-1.5 rounded-lg text-xs whitespace-nowrap transition-all">
                                {{ $catLabel }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Tombol Tambah Baris --}}
                    @if(!$useSheet)
                        <button @click="openAddModal(activeTab)"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-xs transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Baris
                        </button>
                    @endif
                </div>
            </div>

            {{-- Table Body --}}
            <div class="bg-white">
                @php
                    $categories = $useSheet ? $mentorPerformance->categories : $dbPerformance;
                @endphp

                @foreach(['tk_pu_aktif' => 'TK PU AKTIF', 'bpu_aktif' => 'TK BPU AKTIF', 'iuran' => 'IURAN'] as $catKey => $catLabel)
                    @php
                        $cat = $categories[$catKey] ?? null;
                        $mentors = is_array($cat) ? ($cat['mentors'] ?? []) : [];
                        $total   = is_array($cat) ? ($cat['total'] ?? null) : null;
                    @endphp
                    <div x-show="activeTab === '{{ $catKey }}'" style="display: none;">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] text-left text-sm">
                                <thead>
                                    <tr class="bg-gray-50/80 border-b border-gray-100 text-gray-500">
                                        <th class="px-6 py-3.5 text-center w-14 font-bold text-xs uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3.5 font-bold text-xs uppercase tracking-wider">Nama Mentor (ARK)</th>
                                        <th class="px-6 py-3.5 font-bold text-xs uppercase tracking-wider text-right">Target</th>
                                        <th class="px-6 py-3.5 font-bold text-xs uppercase tracking-wider text-right">Realisasi</th>
                                        <th class="px-6 py-3.5 font-bold text-xs uppercase tracking-wider text-right">GAP</th>
                                        <th class="px-6 py-3.5 font-bold text-xs uppercase tracking-wider text-center">% Capaian</th>
                                        @if(!$useSheet)
                                            <th class="px-6 py-3.5 font-bold text-xs uppercase tracking-wider text-center">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($mentors as $mentor)
                                        @php
                                            $isObj = is_object($mentor);
                                            $mentorNo        = $isObj ? $mentor->no        : ($mentor['no'] ?? '-');
                                            $mentorName      = $isObj ? $mentor->mentor_name ?? $mentor->name : ($mentor['name'] ?? '-');
                                            $mentorTarget    = $isObj ? $mentor->target     : ($mentor['target'] ?? '-');
                                            $mentorRealisasi = $isObj ? $mentor->realisasi  : ($mentor['realisasi'] ?? '-');
                                            $mentorGap       = $isObj ? $mentor->gap        : ($mentor['gap'] ?? '-');
                                            $mentorPercent   = $isObj ? $mentor->percent    : ($mentor['percent'] ?? '-');
                                            $mentorId        = $isObj && isset($mentor->id) ? $mentor->id : null;

                                            $percentNum = (float) rtrim(str_replace(['%', ','], ['', '.'], $mentorPercent));
                                            $badgeClass = $percentNum >= 90 ? 'bg-green-100 text-green-800' : ($percentNum >= 50 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700');
                                        @endphp
                                        <tr class="hover:bg-gray-50/50 transition-colors group">
                                            <td class="px-6 py-4 text-center font-semibold text-gray-400 text-xs">{{ $mentorNo }}</td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-xl bg-green-100 text-green-700 flex items-center justify-center font-extrabold text-xs shrink-0">
                                                        {{ strtoupper(substr($mentorName, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-gray-900">{{ $mentorName }}</p>
                                                        <p class="text-xs text-gray-400">Account Representative Khusus</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-right text-gray-600 font-medium">{{ $mentorTarget }}</td>
                                            <td class="px-6 py-4 text-right font-bold text-gray-900">{{ $mentorRealisasi }}</td>
                                            <td class="px-6 py-4 text-right font-medium {{ str_contains((string)$mentorGap, '(') || str_starts_with((string)$mentorGap, '-') ? 'text-red-500' : 'text-green-600' }}">
                                                {{ $mentorGap }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $badgeClass }}">
                                                    {{ $mentorPercent }}
                                                </span>
                                            </td>
                                            @if(!$useSheet && $mentorId)
                                                <td class="px-6 py-4 text-center">
                                                    <div class="flex items-center justify-center gap-1.5">
                                                        {{-- Tombol Edit --}}
                                                        <button @click="openEditModal({
                                                                    id: {{ $mentorId }},
                                                                    mentor_name: '{{ addslashes($mentorName) }}',
                                                                    category: '{{ $catKey }}',
                                                                    target: '{{ addslashes($mentorTarget) }}',
                                                                    realisasi: '{{ addslashes($mentorRealisasi) }}'
                                                                })"
                                                                class="p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition-colors"
                                                                title="Edit">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                        </button>
                                                        {{-- Tombol Hapus --}}
                                                        <button @click="confirmDelete({{ $mentorId }}, '{{ addslashes($mentorName) }}')"
                                                                class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition-colors"
                                                                title="Hapus">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ $useSheet ? 6 : 7 }}" class="px-8 py-10 text-center text-gray-400">
                                                @if($useSheet)
                                                    Data dari spreadsheet kosong.
                                                @else
                                                    Belum ada data. Klik <strong>"Tambah Baris"</strong> atau <strong>"Sinkron Spreadsheet"</strong>.
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($total)
                                    <tfoot class="bg-gray-50/90 font-bold border-t border-gray-200 text-gray-800">
                                        <tr>
                                            <td colspan="2" class="px-6 py-4 uppercase text-xs tracking-wider text-gray-700">
                                                Total {{ $catLabel }}
                                            </td>
                                            <td class="px-6 py-4 text-right text-gray-700">{{ $total->target }}</td>
                                            <td class="px-6 py-4 text-right text-gray-900 font-extrabold">{{ $total->realisasi }}</td>
                                            <td class="px-6 py-4 text-right {{ str_contains((string)$total->gap, '(') || str_starts_with((string)$total->gap, '-') ? 'text-red-500' : 'text-green-600' }}">
                                                {{ $total->gap }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-green-600 text-white">
                                                    {{ $total->percent }}
                                                </span>
                                            </td>
                                            @if(!$useSheet)
                                                <td class="px-6 py-4"></td>
                                            @endif
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ── Daftar Akun Mentor ────────────────────────────────────────────── --}}
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
                <table class="w-full min-w-[600px] text-left text-sm">
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
                                <td class="px-6 sm:px-8 py-4 text-gray-600 font-medium">{{ $m->email }}</td>
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
                                <td colspan="4" class="px-8 py-10 text-center text-gray-400">Belum ada akun mentor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        {{-- MODAL: Edit Data Performa Mentor --}}
        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        <div x-show="showEditModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="display: none;">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showEditModal = false"></div>

            {{-- Modal Panel --}}
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md z-10 overflow-hidden">
                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-blue-600 to-blue-700">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-white">Edit Data Mentor</h3>
                            <p class="text-xs text-blue-200" x-text="'Kategori: ' + categoryLabel"></p>
                        </div>
                    </div>
                    <button @click="showEditModal = false" class="text-white/70 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Form --}}
                <form :action="'/superadmin/mentor-performance/' + editData.id" method="POST" class="px-6 py-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wider">Nama Mentor (ARK)</label>
                        <input type="text" name="mentor_name" x-model="editData.mentor_name" required
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all"
                               placeholder="Nama ARK...">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wider">Target</label>
                            <input type="text" name="target" x-model="editData.target" required
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all"
                                   placeholder="Contoh: 150">
                            <p class="text-xs text-gray-400 mt-1">Angka, tanpa titik/koma</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wider">Realisasi</label>
                            <input type="text" name="realisasi" x-model="editData.realisasi" required
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all"
                                   placeholder="Contoh: 132">
                            <p class="text-xs text-gray-400 mt-1">GAP & % dihitung otomatis</p>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-xl p-3 text-xs text-blue-700 font-medium">
                        💡 GAP dan % Capaian akan dihitung ulang secara otomatis saat disimpan.
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showEditModal = false"
                                class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        {{-- MODAL: Tambah Data Performa Mentor --}}
        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        <div x-show="showAddModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="display: none;">

            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showAddModal = false"></div>

            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md z-10 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-green-600 to-green-700">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-white">Tambah Data Mentor</h3>
                            <p class="text-xs text-green-200" x-text="'Kategori: ' + categoryLabel"></p>
                        </div>
                    </div>
                    <button @click="showAddModal = false" class="text-white/70 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('superadmin.mentor-performance.store') }}" method="POST" class="px-6 py-5 space-y-4">
                    @csrf
                    <input type="hidden" name="category" x-model="addData.category">

                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wider">Nama Mentor (ARK)</label>
                        <input type="text" name="mentor_name" x-model="addData.mentor_name" required
                               list="mentor-names-list"
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-900 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all"
                               placeholder="Nama ARK...">
                        <datalist id="mentor-names-list">
                            @foreach($mentorNames as $mn)
                                <option value="{{ $mn }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wider">Target</label>
                            <input type="text" name="target" x-model="addData.target" required
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-900 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all"
                                   placeholder="Contoh: 150">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wider">Realisasi</label>
                            <input type="text" name="realisasi" x-model="addData.realisasi" required
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-900 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all"
                                   placeholder="Contoh: 132">
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-xl p-3 text-xs text-green-700 font-medium">
                        💡 GAP dan % Capaian akan dihitung otomatis. Nomor urut ditentukan sistem.
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showAddModal = false"
                                class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-bold transition-colors">
                            Tambahkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Form hapus (hidden) --}}
        <form id="delete-form" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>

    </div>

    @push('scripts')
    <script>
    function mentorMonitoring() {
        return {
            showEditModal: false,
            showAddModal: false,
            categoryLabel: '',
            editData: { id: null, mentor_name: '', category: '', target: '', realisasi: '' },
            addData:  { category: '', mentor_name: '', target: '', realisasi: '' },

            categoryLabels: {
                'tk_pu_aktif': 'TK PU AKTIF',
                'bpu_aktif':   'TK BPU AKTIF',
                'iuran':       'IURAN'
            },

            openEditModal(data) {
                this.editData = { ...data };
                this.categoryLabel = this.categoryLabels[data.category] || data.category;
                this.showEditModal = true;
            },

            openAddModal(cat) {
                this.addData = { category: cat, mentor_name: '', target: '', realisasi: '' };
                this.categoryLabel = this.categoryLabels[cat] || cat;
                this.showAddModal = true;
            },

            confirmDelete(id, name) {
                if (!confirm(`Hapus data "${name}"? Tindakan ini tidak dapat dibatalkan.`)) return;
                const form = document.getElementById('delete-form');
                form.action = `/superadmin/mentor-performance/${id}`;
                form.submit();
            }
        }
    }
    </script>
    @endpush
</x-app-layout>
