<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </h2>
    </x-slot>

    <div class="page-wrapper">

        {{-- ── Hero Welcome Card ────────────────────────────────────────── --}}
        <div class="relative overflow-hidden rounded-3xl shadow-lg"
             style="background: linear-gradient(135deg, #16a34a 0%, #15803d 50%, #166534 100%);">
            {{-- decorative blobs --}}
            <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-white/10 pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>

            <div class="relative z-10 p-8 sm:p-10 flex flex-col sm:flex-row items-start sm:items-center gap-6">
                {{-- avatar --}}
                <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center text-white shadow-lg shrink-0 text-2xl font-extrabold overflow-hidden">
                    @if(Auth::user()->photo)
                        <img src="{{ Storage::url(Auth::user()->photo) }}" alt="Foto Profil" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <p class="text-green-200 text-sm font-semibold uppercase tracking-widest mb-1">Selamat Datang</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white mb-1">{{ Auth::user()->name }}</h3>
                    <p class="text-green-100/80 text-sm sm:text-base">Portal Magang BPJS Ketenagakerjaan — Jangan lupa absensi &amp; To-Do List hari ini!</p>
                </div>
                {{-- right badge --}}
                <div class="sm:ml-auto shrink-0">
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/15 backdrop-blur-sm border border-white/25 rounded-2xl text-white text-sm font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ── Stats Row ─────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            {{-- Total BPU --}}
            <div class="glass-effect rounded-2xl p-6 card-hover flex items-center gap-5">
                <div class="stat-icon bg-blue-50 text-blue-600">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-0.5">Total Peserta BPU</p>
                    <p class="text-3xl font-extrabold text-gray-900 leading-none">{{ $totalBpu }}</p>
                    <p class="text-xs text-gray-400 mt-0.5 font-medium">Orang</p>
                </div>
            </div>

            {{-- Quick link: Absensi --}}
            <a href="{{ route('attendances.index') }}"
               class="glass-effect rounded-2xl p-6 card-hover flex items-center gap-5 group">
                <div class="stat-icon bg-green-50 text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-0.5">Absensi Harian</p>
                    <p class="text-base font-bold text-gray-800 group-hover:text-green-700 transition-colors">Buka Halaman →</p>
                </div>
            </a>

            {{-- Quick link: Tugas --}}
            <a href="{{ route('tasks.index') }}"
               class="glass-effect rounded-2xl p-6 card-hover flex items-center gap-5 group">
                <div class="stat-icon bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-0.5">Pengumpulan Tugas</p>
                    <p class="text-base font-bold text-gray-800 group-hover:text-purple-700 transition-colors">Buka Halaman →</p>
                </div>
            </a>
        </div>

        {{-- ── History Cards ──────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Riwayat Absensi --}}
            <div class="glass-effect rounded-3xl overflow-hidden shadow-md card-hover flex flex-col">
                {{-- header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100/70 bg-gradient-to-r from-green-50/60 to-transparent">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-base font-extrabold text-gray-900">Riwayat Absensi</h3>
                    </div>
                    <a href="{{ route('attendances.index') }}"
                       class="text-xs font-bold text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 px-3 py-1.5 rounded-lg transition-colors">
                        Lihat Semua →
                    </a>
                </div>

                {{-- body --}}
                <div class="p-5 flex-1 space-y-3">
                    @forelse($attendances as $attendance)
                        <div class="flex items-center gap-4 bg-white rounded-2xl px-4 py-3.5 border border-gray-100 hover:border-green-200 hover:shadow-sm transition-all">
                            {{-- colour stripe --}}
                            <div class="w-1.5 h-10 bg-gradient-to-b from-green-400 to-green-600 rounded-full shrink-0"></div>
                            {{-- date + type --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-gray-900 text-sm truncate">{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('l, d F Y') }}</p>
                                <span class="badge bg-green-100 text-green-800 capitalize mt-1">{{ str_replace('_', ' ', $attendance->type) }}</span>
                            </div>
                            {{-- times --}}
                            <div class="flex items-center gap-3 shrink-0">
                                <div class="text-center">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">In</p>
                                    <p class="text-sm font-extrabold text-green-600">{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '--:--' }}</p>
                                </div>
                                <div class="w-px h-8 bg-gray-200"></div>
                                <div class="text-center">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Out</p>
                                    <p class="text-sm font-extrabold text-red-500">{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '--:--' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gray-100 text-gray-300 mb-3">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-400">Belum ada riwayat absensi.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Riwayat Tugas --}}
            <div class="glass-effect rounded-3xl overflow-hidden shadow-md card-hover flex flex-col">
                {{-- header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100/70 bg-gradient-to-r from-blue-50/60 to-transparent">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <h3 class="text-base font-extrabold text-gray-900">Riwayat Tugas</h3>
                    </div>
                    <a href="{{ route('tasks.index') }}"
                       class="text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded-lg transition-colors">
                        Lihat Semua →
                    </a>
                </div>

                {{-- body --}}
                <div class="p-5 flex-1 space-y-3">
                    @forelse($tasks as $task)
                        <a href="{{ $task->link }}" target="_blank" class="block group">
                            <div class="flex items-center gap-4 bg-white rounded-2xl px-4 py-3.5 border border-gray-100 hover:border-blue-200 hover:shadow-sm transition-all">
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-900 text-sm group-hover:text-blue-700 transition-colors">
                                        {{ \Carbon\Carbon::parse($task->submitted_at)->translatedFormat('d M Y') }}
                                        <span class="text-gray-400 font-medium ml-1">{{ \Carbon\Carbon::parse($task->submitted_at)->format('H:i') }}</span>
                                    </p>
                                    <p class="text-xs text-blue-500 font-medium mt-1 truncate">{{ $task->link }}</p>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-blue-50 group-hover:bg-blue-600 flex items-center justify-center text-blue-500 group-hover:text-white transition-colors shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-10">
                            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gray-100 text-gray-300 mb-3">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-400">Belum ada tugas yang dikumpulkan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
