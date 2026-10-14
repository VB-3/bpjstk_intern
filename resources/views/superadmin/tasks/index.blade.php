<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Kelola Tugas
            </h2>
            <a href="{{ route('superadmin.tasks.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-white shadow-md shadow-green-200 hover:shadow-lg transition-all"
                style="background: linear-gradient(135deg, #16a34a, #15803d);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Tugas Baru
            </a>
        </div>
    </x-slot>

    <div class="page-wrapper space-y-8">

        {{-- ── SECTION 0: TUGAS MENUNGGU PERSETUJUAN ─────────────────────── --}}
        @if($pendingTasks->count() > 0)
        <div class="glass-effect rounded-3xl overflow-hidden shadow-md border border-yellow-200 bg-yellow-50/50">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-yellow-200/70 bg-yellow-50">
                <div class="w-9 h-9 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-yellow-900">Menunggu Persetujuan <span class="ml-2 px-2 py-0.5 rounded-full bg-yellow-200 text-yellow-800 text-xs font-bold">{{ $pendingTasks->count() }}</span></h3>
                    <p class="text-xs text-yellow-700 mt-0.5">Tugas berikut dikirim oleh Mentor dan membutuhkan persetujuan Anda.</p>
                </div>
            </div>
            <div class="divide-y divide-yellow-100">
                @foreach($pendingTasks as $task)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 bg-white/70 hover:bg-yellow-50/50 transition-colors">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h4 class="font-bold text-gray-900">{{ $task->title }}</h4>
                        </div>
                        @if($task->description)
                            <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $task->description }}</p>
                        @endif
                        <div class="flex flex-wrap items-center gap-4 mt-2">
                            <span class="text-xs text-gray-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Oleh: <span class="font-semibold text-gray-700">{{ $task->creator?->name ?? '-' }}</span>
                            </span>
                            @if($task->deadline)
                            <span class="text-xs text-red-600 flex items-center gap-1 font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Deadline: {{ \Carbon\Carbon::parse($task->deadline)->format('d/m/Y H:i') }}
                            </span>
                            @endif
                            <span class="text-xs text-gray-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ \Carbon\Carbon::parse($task->created_at)->translatedFormat('d M Y, H:i') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <form action="{{ route('superadmin.tasks.approve', $task->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold text-white bg-green-600 hover:bg-green-700 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Setujui
                            </button>
                        </form>
                        <form action="{{ route('superadmin.tasks.reject', $task->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold text-yellow-800 bg-yellow-300 hover:bg-yellow-400 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tolak
                            </button>
                        </form>
                        <form action="{{ route('superadmin.tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tugas ini secara permanen?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold text-white bg-red-500 hover:bg-red-600 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            {{-- Pagination Pending --}}
            <div class="px-6 pb-4 pt-2 bg-yellow-50/30">
                {{ $pendingTasks->links('vendor.pagination.custom') }}
            </div>
        </div>
        @endif

        {{-- ── SECTION 1: DAFTAR TUGAS ─────────────────────────────────── --}}
        <div class="glass-effect rounded-3xl overflow-hidden shadow-md border border-white/60">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 border-b border-gray-100/70 bg-white/80">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-green-100 text-green-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900">Daftar Tugas</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Penugasan materi dan proyek magang untuk peserta</p>
                    </div>
                </div>
                <a href="{{ route('superadmin.tasks.create') }}"
                    class="sm:hidden inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white shadow-sm"
                    style="background: linear-gradient(135deg, #16a34a, #15803d);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Tugas
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead class="bg-gray-50/75 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Judul Tugas</th>
                            <th class="px-6 py-4">Deskripsi</th>
                            <th class="px-6 py-4">Deadline</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white/70">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-green-50/40 transition-colors">
                                {{-- Judul --}}
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 text-sm max-w-xs truncate">
                                        {{ $task->title }}
                                    </div>
                                    <div class="text-[11px] text-gray-400 mt-0.5">
                                        Dibuat: {{ \Carbon\Carbon::parse($task->created_at)->format('d/m/Y') }}
                                    </div>
                                </td>

                                {{-- Deskripsi --}}
                                <td class="px-6 py-4">
                                    <p class="text-gray-600 text-sm max-w-md line-clamp-2" title="{{ $task->description }}">
                                        {{ \Illuminate\Support\Str::limit($task->description, 60) ?: '-' }}
                                    </p>
                                </td>

                                {{-- Deadline --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($task->deadline)
                                        <div class="font-semibold text-gray-800">
                                            {{ \Carbon\Carbon::parse($task->deadline)->format('d/m/Y H:i') }} WIB
                                        </div>
                                        <div class="text-[11px] {{ \Carbon\Carbon::parse($task->deadline)->isPast() ? 'text-red-500 font-bold' : 'text-gray-400' }}">
                                            {{ \Carbon\Carbon::parse($task->deadline)->diffForHumans() }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($task->deadline && \Carbon\Carbon::parse($task->deadline)->isPast())
                                        <span class="badge bg-red-100 text-red-700 border border-red-200 inline-flex items-center gap-1 font-bold">
                                            Selesai
                                        </span>
                                    @elseif($task->status === 'approved' || $task->is_active)
                                        <span class="badge bg-green-100 text-green-800 border border-green-200 inline-flex items-center gap-1 font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-600 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    @elseif($task->status === 'rejected')
                                        <span class="badge bg-red-100 text-red-700 border border-red-200">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="badge bg-yellow-100 text-yellow-700 border border-yellow-200">
                                            Menunggu
                                        </span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('superadmin.tasks.edit', $task) }}"
                                            class="p-2 rounded-xl text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 border border-transparent hover:border-emerald-200 transition-colors"
                                            title="Edit Tugas">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <form action="{{ route('superadmin.tasks.destroy', $task) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 rounded-xl text-red-500 hover:text-red-700 hover:bg-red-50 border border-transparent hover:border-red-200 transition-colors"
                                                title="Hapus Tugas">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-100 text-gray-300 mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-gray-700 mb-1">Belum Ada Tugas</h3>
                                    <p class="text-sm text-gray-400">Belum ada tugas yang dibuat. Silakan tambahkan tugas baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Tasks --}}
            <div class="px-6 py-4 bg-white/50 border-t border-gray-100/70">
                {{ $tasks->links('vendor.pagination.custom') }}
            </div>
        </div>

        {{-- ── SECTION 2: PENGUMPULAN TUGAS PESERTA ──────────────────────── --}}
        <div class="glass-effect rounded-3xl overflow-hidden shadow-md border border-white/60">
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100/70 bg-white/80">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900">Pengumpulan Tugas Peserta</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Daftar berkas dan tautan tugas yang diserahkan oleh peserta magang</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead class="bg-gray-50/75 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Peserta</th>
                            <th class="px-6 py-4">Link Tugas</th>
                            <th class="px-6 py-4">Waktu Submit</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white/70">
                        @forelse($submissions as $submission)
                            <tr class="hover:bg-blue-50/40 transition-colors">
                                {{-- Peserta --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-700 text-white font-extrabold text-sm flex items-center justify-center shrink-0 shadow-sm">
                                            {{ strtoupper(substr($submission->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-900 truncate">{{ $submission->user->name ?? 'User Dihapus' }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $submission->user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Link Tugas --}}
                                <td class="px-6 py-4">
                                    <a href="{{ $submission->link }}" target="_blank" rel="noopener noreferrer"
                                        class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-800 font-semibold hover:underline max-w-sm sm:max-w-md truncate"
                                        title="{{ $submission->link }}">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        <span class="truncate">{{ $submission->link }}</span>
                                    </a>
                                </td>

                                {{-- Waktu Submit --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-800">
                                        {{ $submission->submitted_at ? \Carbon\Carbon::parse($submission->submitted_at)->diffForHumans() : '-' }}
                                    </div>
                                    <div class="text-[11px] text-gray-400">
                                        {{ $submission->submitted_at ? \Carbon\Carbon::parse($submission->submitted_at)->format('d/m/Y H:i') : '' }} WIB
                                    </div>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <form action="{{ route('superadmin.submissions.destroy', $submission) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengumpulan tugas ini?');"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 rounded-xl text-red-500 hover:text-red-700 hover:bg-red-50 border border-transparent hover:border-red-200 transition-colors"
                                            title="Hapus Pengumpulan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-100 text-gray-300 mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-gray-700 mb-1">Belum Ada Pengumpulan Tugas</h3>
                                    <p class="text-sm text-gray-400">Belum ada peserta yang mengumpulkan tugas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Submissions --}}
            <div class="px-6 py-4 bg-white/50 border-t border-gray-100/70">
                {{ $submissions->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</x-app-layout>
