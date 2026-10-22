<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Mentor') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">



            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Leaderboard BPU -->
                <button type="button" onclick="document.getElementById('modalLeaderboard').classList.remove('hidden')"
                        class="glass-effect rounded-2xl p-6 border border-amber-100/60 hover:border-amber-300 shadow-sm flex flex-col justify-between group w-full text-left cursor-pointer card-hover transition-all">
                    <div class="flex items-center justify-between w-full mb-2.5">
                        <div class="flex items-center gap-1.5">
                            <span class="text-base leading-none">🏆</span>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Leaderboard BPU</p>
                        </div>
                        <span class="text-[11px] font-bold text-amber-600 group-hover:text-amber-700 transition-colors flex items-center gap-0.5">
                            Lihat Semua <span class="group-hover:translate-x-0.5 transition-transform duration-200">→</span>
                        </span>
                    </div>
                    <div class="w-full space-y-1.5">
                        @forelse($bpuTop3 as $idx => $top)
                            <div class="flex items-center justify-between text-xs py-1.5 px-2.5 rounded-xl bg-gray-50/90 group-hover:bg-amber-50/60 transition-colors">
                                <span class="font-semibold text-gray-800 truncate flex items-center gap-2">
                                    <span class="text-sm leading-none">{{ ['🥇','🥈','🥉'][$idx] }}</span>
                                    <span class="truncate">{{ $top->name }}</span>
                                </span>
                                <span class="font-extrabold text-amber-700 shrink-0 ml-2 bg-white border border-amber-200/70 px-2 py-0.5 rounded-lg text-[11px]">
                                    {{ $top->total_bpu }} <span class="text-[9px] font-semibold text-gray-400">BPU</span>
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 py-1">Belum ada data BPU.</p>
                        @endforelse
                    </div>
                </button>

                <!-- Total Interns -->
                <div class="glass-effect rounded-2xl p-6 border border-white/50 shadow-sm flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Total Peserta</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $totalInterns }}</p>
                    </div>
                </div>

                <!-- Total Attendances Today -->
                <div class="glass-effect rounded-2xl p-6 border border-white/50 shadow-sm flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center justify-center text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Hadir Hari Ini</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $totalAttendancesToday }}</p>
                    </div>
                </div>

                <!-- Total Tasks Submitted -->
                <div class="glass-effect rounded-2xl p-6 border border-white/50 shadow-sm flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Total Pengumpulan</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $totalTasksSubmitted }}</p>
                    </div>
                </div>


            </div>

            <!-- Modal Leaderboard BPU -->
            <div id="modalLeaderboard"
                 class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
                 onclick="if(event.target===this) this.classList.add('hidden')">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

                <div class="relative z-10 bg-white rounded-3xl shadow-2xl w-full max-w-md max-h-[85vh] flex flex-col overflow-hidden">
                    <!-- Header -->
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

                    <!-- Body -->
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

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 text-center">
                        <p class="text-xs text-gray-400">Total BPU seluruh peserta: <strong class="text-gray-700">{{ $totalBpu }}</strong> orang</p>
                    </div>
                </div>
            </div>



            <!-- Interns Recap Table -->
            <div class="glass-effect rounded-3xl overflow-hidden shadow-lg border border-white/50">
                <div class="bg-white p-6 sm:px-8 border-b border-gray-100">
                    <h3 class="text-xl font-extrabold text-gray-900">Rekapitulasi Peserta Magang</h3>
                    <p class="text-sm text-gray-500 mt-1">Daftar seluruh peserta beserta statistik kehadiran dan tugas mereka.</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-left text-sm">
                        <thead class="bg-gray-50/50 text-gray-600 font-bold border-b border-gray-100">
                            <tr>
                                <th class="px-8 py-4">Nama Peserta</th>
                                <th class="px-8 py-4">Link To-Do List</th>
                                <th class="px-8 py-4 text-center">Total Kehadiran</th>
                                <th class="px-8 py-4 text-center">Total Tugas Dikumpulkan</th>
                                <th class="px-8 py-4 text-center">Total BPU</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($interns as $intern)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold">
                                                {{ substr($intern->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $intern->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $intern->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        @if($intern->gdrive_link)
                                            <a href="{{ $intern->formatted_gdrive_link ?? $intern->gdrive_link }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 transition-colors shadow-sm">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                Buka G-Drive
                                            </a>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium text-gray-400 bg-gray-50 border border-gray-200 italic">
                                                Belum ada link
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                            {{ $intern->attendances_count }} Hari
                                        </span>
                                    </td>
                                    <td class="px-8 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-blue-100 text-blue-800">
                                            {{ $intern->task_submissions_count }} Tugas
                                        </span>
                                    </td>
                                    <td class="px-8 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-orange-100 text-orange-800">
                                            {{ $intern->todos_sum_bpu_achievement ?? 0 }} Orang
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-10 text-center text-gray-500">
                                        Belum ada peserta magang yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Task Submissions Table -->
            <div class="glass-effect rounded-3xl overflow-hidden shadow-lg border border-white/50 mt-8">
                <div class="bg-white p-6 sm:px-8 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-extrabold text-gray-900">Tugas yang Dikumpulkan</h3>
                        <p class="text-sm text-gray-500 mt-1">Daftar tugas yang baru saja disubmit oleh peserta.</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-left text-sm">
                        <thead class="bg-gray-50/50 text-gray-600 font-bold border-b border-gray-100">
                            <tr>
                                <th class="px-8 py-4">Peserta</th>
                                <th class="px-8 py-4">Link Tugas</th>
                                <th class="px-8 py-4 text-center">Waktu Submit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($recentSubmissions as $submission)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                                {{ substr($submission->user->name ?? '?', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $submission->user->name ?? 'Unknown' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <a href="{{ $submission->link }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1 font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            Lihat Tugas
                                        </a>
                                    </td>
                                    <td class="px-8 py-4 text-center">
                                        <span class="text-gray-500 text-xs font-medium">
                                            {{ \Carbon\Carbon::parse($submission->submitted_at)->diffForHumans() }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-10 text-center text-gray-500">
                                        Belum ada tugas yang dikumpulkan oleh peserta.
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

