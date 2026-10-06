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

                <!-- Total BPU -->
                <div class="glass-effect rounded-2xl p-6 border border-white/50 shadow-sm flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Total BPU</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $totalBpu }}</p>
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
                                            <a href="{{ $intern->gdrive_link }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1 font-medium text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                Lihat G-Drive
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-sm italic">Belum ada</span>
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

