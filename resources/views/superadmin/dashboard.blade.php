<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Dashboard Super Admin
        </h2>
    </x-slot>

    <div class="page-wrapper">

        {{-- ── Hero Welcome Card ────────────────────────────────────────── --}}
        <div class="relative overflow-hidden rounded-3xl shadow-lg"
             style="background: linear-gradient(135deg, #15803d 0%, #166534 50%, #14532d 100%);">
            <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-white/10 pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>

            <div class="relative z-10 p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center text-white shadow-lg shrink-0 text-2xl font-extrabold overflow-hidden">
                        @if(Auth::user()->photo)
                            <img src="{{ Storage::url(Auth::user()->photo) }}" alt="Foto Profil" class="w-full h-full object-cover">
                        @else
                            <svg class="w-8 h-8 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-400/20 text-yellow-300 border border-yellow-300/30">Super Administrator</span>
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-white mt-1">Panel Kendali Sistem</h3>
                        <p class="text-green-100/80 text-sm mt-0.5">Ringkasan menyeluruh data pengguna, absensi, dan aktivitas magang.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('superadmin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-green-800 hover:bg-green-50 font-bold rounded-xl text-sm shadow-md transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Pengguna
                    </a>
                </div>
            </div>
        </div>

        {{-- ── Stat Cards Row 1: Users Stats ────────────────────────────── --}}
        <div>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Statistik Pengguna</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                {{-- Total Pengguna --}}
                <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                    <div class="stat-icon bg-blue-50 text-blue-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Total Pengguna</p>
                        <p class="text-3xl font-extrabold text-gray-900 mt-0.5">{{ $totalUsers }}</p>
                    </div>
                </div>

                {{-- Total Peserta Magang --}}
                <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                    <div class="stat-icon bg-green-50 text-green-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Total Peserta Magang</p>
                        <p class="text-3xl font-extrabold text-gray-900 mt-0.5">{{ $totalInterns }}</p>
                    </div>
                </div>

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

            </div>
        </div>

        {{-- ── Stat Cards Row 2: Activity Stats ─────────────────────────── --}}
        <div>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Statistik Aktivitas & Kinerja</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                {{-- Total BPU / Leaderboard Card --}}
                <button type="button" onclick="document.getElementById('modalLeaderboard').classList.remove('hidden')"
                        class="glass-effect rounded-2xl p-5 card-hover flex flex-col justify-between group w-full text-left cursor-pointer border border-amber-100/60 hover:border-amber-300 transition-all">
                    <div class="flex items-center justify-between w-full mb-2">
                        <div class="flex items-center gap-1.5">
                            <span class="text-base leading-none">🏆</span>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Leaderboard BPU</p>
                        </div>
                        <span class="text-[10px] font-bold text-amber-600 group-hover:text-amber-700 transition-colors flex items-center gap-0.5">
                            Lihat Semua <span class="group-hover:translate-x-0.5 transition-transform duration-200">→</span>
                        </span>
                    </div>
                    <div class="w-full space-y-1.5">
                        @forelse($bpuTop3 as $idx => $top)
                            <div class="flex items-center justify-between text-xs py-1 px-2 rounded-lg bg-gray-50/90 group-hover:bg-amber-50/60 transition-colors">
                                <span class="font-semibold text-gray-800 truncate flex items-center gap-1.5">
                                    <span class="text-xs leading-none">{{ ['🥇','🥈','🥉'][$idx] }}</span>
                                    <span class="truncate">{{ $top->name }}</span>
                                </span>
                                <span class="font-extrabold text-amber-700 shrink-0 ml-1.5 bg-white border border-amber-200/70 px-1.5 py-0.5 rounded-md text-[10px]">
                                    {{ $top->total_bpu }} <span class="text-[9px] font-semibold text-gray-400">BPU</span>
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 py-1">Belum ada data BPU.</p>
                        @endforelse
                    </div>
                </button>

                {{-- Hadir Hari Ini --}}
                <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                    <div class="stat-icon bg-emerald-50 text-emerald-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Hadir Hari Ini</p>
                        <p class="text-3xl font-extrabold text-gray-900 mt-0.5">{{ $totalAttendancesToday }}</p>
                    </div>
                </div>

                {{-- Total Pengumpulan Tugas --}}
                <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                    <div class="stat-icon bg-amber-50 text-amber-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Total Pengumpulan Tugas</p>
                        <p class="text-3xl font-extrabold text-gray-900 mt-0.5">{{ $totalTasksSubmitted }}</p>
                    </div>
                </div>

                {{-- Total Tugas Dibuat --}}
                <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                    <div class="stat-icon bg-rose-50 text-rose-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Total Tugas Dibuat</p>
                        <p class="text-3xl font-extrabold text-gray-900 mt-0.5">{{ $totalAssignedTasks }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Modal Leaderboard BPU ───────────────────────────────────────── --}}
        <div id="modalLeaderboard"
             class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
             onclick="if(event.target===this) this.classList.add('hidden')">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

            <div class="relative z-10 bg-white rounded-3xl shadow-2xl w-full max-w-md max-h-[85vh] flex flex-col overflow-hidden">
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100"
                     style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-xl">
                            🏆
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-white">Leaderboard BPU Peserta Magang</h3>
                            <p class="text-xs text-yellow-100">Diurutkan dari BPU terbanyak</p>
                        </div>
                    </div>
                    <button onclick="document.getElementById('modalLeaderboard').classList.add('hidden')"
                            class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="overflow-y-auto flex-1 p-4 space-y-2">
                    @forelse($bpuLeaderboard as $rank => $peserta)
                        @php
                            $medals = ['🥇','🥈','🥉'];
                            $medal = $medals[$rank] ?? null;
                            $isTop = $rank < 3;
                        @endphp
                        <div class="flex items-center gap-4 rounded-2xl px-4 py-3
                            {{ $isTop ? 'bg-yellow-50 border border-yellow-200' : 'bg-gray-50 border border-gray-100' }}">
                            <div class="w-8 text-center shrink-0">
                                @if($medal)
                                    <span class="text-xl">{{ $medal }}</span>
                                @else
                                    <span class="text-sm font-bold text-gray-400">#{{ $rank + 1 }}</span>
                                @endif
                            </div>
                            <div class="w-9 h-9 rounded-xl {{ $isTop ? 'bg-yellow-200 text-yellow-800' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center font-extrabold text-sm shrink-0">
                                {{ strtoupper(substr($peserta->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-gray-900 text-sm truncate">{{ $peserta->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $peserta->email }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-xl font-extrabold {{ $isTop ? 'text-yellow-600' : 'text-gray-700' }}">{{ $peserta->total_bpu }}</p>
                                <p class="text-[10px] text-gray-400 font-medium">BPU</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-gray-400 text-sm">Belum ada data BPU peserta magang.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-400">Total BPU seluruh peserta: <strong class="text-gray-700">{{ $totalBpu }}</strong> orang</p>
                </div>
            </div>
        </div>



        {{-- ── Riwayat Tugas: Tugas Aktif vs Tugas Selesai ──────────────── --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Riwayat & Status Tugas</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Pemantauan tugas aktif yang sedang berjalan dan tugas yang telah melewati batas tanggal</p>
                </div>
                <a href="{{ route('superadmin.tasks.index') }}" class="text-xs font-bold text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 px-3 py-1.5 rounded-lg transition-colors">
                    Kelola Semua Tugas →
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- 🟢 Card Tugas Aktif --}}
                <div class="glass-effect rounded-3xl overflow-hidden shadow-md flex flex-col border border-emerald-100/60">
                    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100/70 bg-gradient-to-r from-emerald-50/70 to-transparent">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-gray-900 flex items-center gap-2">
                                    Tugas Aktif
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                                        {{ $activeTasks->count() }} Berjalan
                                    </span>
                                </h3>
                                <p class="text-xs text-gray-400">Tugas yang masih berjalan dan belum melewati batas waktu</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 flex-1 divide-y divide-gray-100">
                        @forelse($activeTasks as $task)
                            <div class="py-3.5 first:pt-0 last:pb-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-extrabold text-gray-900 text-sm truncate">{{ $task->title }}</h4>
                                        @if($task->description)
                                            <p class="text-xs text-gray-500 line-clamp-1 mt-0.5">{{ $task->description }}</p>
                                        @endif
                                        <div class="flex flex-wrap items-center gap-2 mt-2">
                                            @if($task->deadline)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-800 bg-emerald-50 border border-emerald-200/80 px-2 py-0.5 rounded-md">
                                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Tenggat: {{ \Carbon\Carbon::parse($task->deadline)->translatedFormat('d M Y, H:i') }} WIB ({{ \Carbon\Carbon::parse($task->deadline)->diffForHumans() }})
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-gray-600 bg-gray-100 px-2 py-0.5 rounded-md">
                                                    Tanpa batas tenggat
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200/70 px-2 py-0.5 rounded-md">
                                                <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                {{ $task->submissions_count }} Pengumpulan
                                            </span>
                                            <span class="text-[10px] text-gray-400">
                                                Oleh: {{ $task->creator->name ?? 'Super Admin' }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('superadmin.tasks.edit', $task) }}" class="p-2 rounded-xl text-gray-400 hover:text-green-700 hover:bg-green-50 transition-colors shrink-0" title="Edit Tugas">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-700">Tidak ada tugas aktif</p>
                                <p class="text-xs text-gray-400 mt-0.5">Semua tugas saat ini telah melewati tenggat atau belum ada tugas baru.</p>
                                <a href="{{ route('superadmin.tasks.create') }}" class="inline-flex items-center gap-1.5 mt-3 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-lg transition-colors">
                                    + Buat Tugas Baru
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- ⚪ Card Tugas Selesai (Melewati Batas Tanggal) --}}
                <div class="glass-effect rounded-3xl overflow-hidden shadow-md flex flex-col border border-gray-200/70">
                    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100/70 bg-gradient-to-r from-gray-50/80 to-transparent">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-gray-900 flex items-center gap-2">
                                    Tugas Selesai
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-gray-100 text-gray-700">
                                        {{ $completedTasks->count() }} Selesai
                                    </span>
                                </h3>
                                <p class="text-xs text-gray-400">Tugas yang telah melewati batas tenggat waktu (deadline)</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 flex-1 divide-y divide-gray-100">
                        @forelse($completedTasks as $task)
                            <div class="py-3.5 first:pt-0 last:pb-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-extrabold text-gray-700 text-sm truncate">{{ $task->title }}</h4>
                                        @if($task->description)
                                            <p class="text-xs text-gray-400 line-clamp-1 mt-0.5">{{ $task->description }}</p>
                                        @endif
                                        <div class="flex flex-wrap items-center gap-2 mt-2">
                                            @if($task->deadline)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-700 bg-red-50 border border-red-200/80 px-2 py-0.5 rounded-md">
                                                    <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Tenggat Berakhir: {{ \Carbon\Carbon::parse($task->deadline)->translatedFormat('d M Y') }} ({{ \Carbon\Carbon::parse($task->deadline)->diffForHumans() }})
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md">
                                                    Selesai
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-gray-700 bg-gray-100 border border-gray-200 px-2 py-0.5 rounded-md">
                                                <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                {{ $task->submissions_count }} Pengumpulan
                                            </span>
                                            <span class="text-[10px] text-gray-400">
                                                Oleh: {{ $task->creator->name ?? 'Super Admin' }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('superadmin.tasks.edit', $task) }}" class="p-2 rounded-xl text-gray-400 hover:text-blue-700 hover:bg-blue-50 transition-colors shrink-0" title="Detail / Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-700">Belum ada tugas selesai</p>
                                <p class="text-xs text-gray-400 mt-0.5">Tugas yang telah melewati tanggal tenggat akan muncul di sini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

        {{-- ── 2 Side-by-Side Tables ────────────────────────────────────── --}}
        @php
            $roleLabels = [
                'intern' => 'Peserta Magang',
                'mentor' => 'Mentor',
                'super_admin' => 'Super Admin',
            ];
            $roleColors = [
                'intern' => 'bg-green-100 text-green-800',
                'mentor' => 'bg-blue-100 text-blue-800',
                'super_admin' => 'bg-yellow-100 text-yellow-800',
            ];
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Pengguna Terbaru --}}
            <div class="glass-effect rounded-3xl overflow-hidden shadow-md flex flex-col">
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100/70 bg-white/80">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900">Pengguna Terbaru</h3>
                            <p class="text-xs text-gray-400">Pengguna yang baru mendaftar atau ditambahkan</p>
                        </div>
                    </div>
                    <a href="{{ route('superadmin.users.index') }}"
                       class="text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded-lg transition-colors">
                        Lihat Semua →
                    </a>
                </div>

                {{-- Table Body --}}
                <div class="overflow-x-auto flex-1">
                    <table class="w-full min-w-[720px] text-left text-sm">
                        <thead>
                            <tr class="bg-gray-50/70 border-b border-gray-100 text-gray-500">
                                <th class="px-6 py-3.5 font-bold text-xs uppercase tracking-wider">Pengguna</th>
                                <th class="px-6 py-3.5 font-bold text-xs uppercase tracking-wider text-center">Role</th>
                                <th class="px-6 py-3.5 font-bold text-xs uppercase tracking-wider text-right">Dibuat</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($recentUsers as $user)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-green-100 text-green-700 flex items-center justify-center font-extrabold text-xs shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-bold text-gray-900 text-sm truncate">{{ $user->name }}</p>
                                                <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="badge {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $roleLabels[$user->role] ?? ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-xs font-medium text-gray-500 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-400 text-sm">
                                        Belum ada data pengguna.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pengumpulan Terbaru --}}
            <div class="glass-effect rounded-3xl overflow-hidden shadow-md flex flex-col">
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100/70 bg-white/80">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900">Pengumpulan Terbaru</h3>
                            <p class="text-xs text-gray-400">Tugas yang baru saja diserahkan peserta</p>
                        </div>
                    </div>
                    <a href="{{ route('superadmin.tasks.index') }}"
                       class="text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-lg transition-colors">
                        Lihat Semua →
                    </a>
                </div>

                {{-- Table Body --}}
                <div class="overflow-x-auto flex-1">
                    <table class="w-full min-w-[720px] text-left text-sm">
                        <thead>
                            <tr class="bg-gray-50/70 border-b border-gray-100 text-gray-500">
                                <th class="px-6 py-3.5 font-bold text-xs uppercase tracking-wider">Peserta</th>
                                <th class="px-6 py-3.5 font-bold text-xs uppercase tracking-wider">Tautan Tugas</th>
                                <th class="px-6 py-3.5 font-bold text-xs uppercase tracking-wider text-right">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($recentSubmissions as $submission)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-extrabold text-xs shrink-0">
                                                {{ strtoupper(substr($submission->user->name ?? '?', 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-bold text-gray-900 text-sm truncate">{{ $submission->user->name ?? 'Pengguna Tidak Diketahui' }}</p>
                                                <p class="text-xs text-gray-400 truncate">{{ $submission->user->email ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ $submission->link }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 border border-blue-100 px-3 py-1.5 rounded-lg transition-colors max-w-xs truncate" title="{{ $submission->link }}">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            <span class="truncate">Buka Tautan</span>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-right text-xs font-semibold text-gray-500 whitespace-nowrap">
                                        {{ $submission->submitted_at ? \Carbon\Carbon::parse($submission->submitted_at)->diffForHumans() : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-400 text-sm">
                                        Belum ada pengumpulan tugas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>

