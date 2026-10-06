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

                {{-- Total BPU --}}
                <div class="glass-effect rounded-2xl p-5 card-hover flex items-center gap-4">
                    <div class="stat-icon bg-orange-50 text-orange-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Total BPU</p>
                        <p class="text-3xl font-extrabold text-gray-900 mt-0.5">{{ $totalBpu }}</p>
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

