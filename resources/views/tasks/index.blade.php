<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Pengumpulan Tugas
        </h2>
    </x-slot>

    <div class="page-wrapper">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            {{-- ── Tugas Aktif Card (sticky) ──────────────────────────────── --}}
            <div class="lg:col-span-1 lg:sticky top-24 space-y-4">

                {{-- gradient header --}}
                <div class="glass-effect rounded-3xl overflow-hidden shadow-md">
                    <div class="relative overflow-hidden px-6 py-6"
                         style="background: linear-gradient(135deg,#3b82f6 0%,#1d4ed8 100%)">
                        <div class="absolute -top-8 -right-8 w-28 h-28 rounded-full bg-white/10"></div>
                        <div class="flex items-center gap-3 relative z-10">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-extrabold text-white">Tugas Aktif</h2>
                                <p class="text-blue-200 text-xs mt-0.5">
                                    {{ $ongoingTasks->count() }} tugas sedang berlangsung
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Flash success message --}}
                @if(session('success'))
                    <div class="px-4 py-3 bg-green-50 border border-green-200 rounded-2xl text-sm text-green-700 font-semibold">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="px-4 py-3 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-700 font-semibold">
                        {{ session('error') }}
                    </div>
                @endif

                @forelse($ongoingTasks as $ongoingTask)
                    @php $alreadySubmitted = in_array($ongoingTask->id, $submittedTaskIds); @endphp
                    <div class="glass-effect rounded-3xl overflow-hidden shadow-md bg-white/90">
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="font-extrabold text-gray-900 text-base mb-1">{{ $ongoingTask->title ?? 'Tugas Baru' }}</h3>
                                <p class="text-sm text-gray-500 leading-relaxed">{{ $ongoingTask->description ?? 'Kerjakan tugas yang diberikan dan kumpulkan link hasil kerja di bawah ini.' }}</p>
                                @if(isset($ongoingTask->deadline))
                                    <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-red-100 text-red-800">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ \Carbon\Carbon::parse($ongoingTask->deadline)->translatedFormat('d F Y, H:i') }} WIB
                                    </div>
                                @endif
                            </div>

                            <div class="border-t border-gray-100 pt-4">
                                @if($alreadySubmitted)
                                    <div class="flex flex-col items-center text-center gap-3 py-4">
                                        <div class="w-12 h-12 rounded-2xl bg-green-100 flex items-center justify-center">
                                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-extrabold text-green-700 text-sm">Sudah Dikumpulkan!</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Tugas ini telah dikumpulkan.</p>
                                        </div>
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('tasks.store') }}" class="space-y-3">
                                        @csrf
                                        <input type="hidden" name="assigned_task_id" value="{{ $ongoingTask->id }}">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Tautan / Link Pengumpulan</label>
                                            <input name="link" type="url" required placeholder="https://..."
                                                class="w-full border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-xl px-4 py-2.5 text-sm font-medium transition-all outline-none">
                                        </div>
                                        <button type="submit"
                                            class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl font-bold text-sm text-white shadow-md shadow-blue-200 transition-all"
                                            style="background:linear-gradient(135deg,#60a5fa,#2563eb)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                            Kirim Tugas
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="glass-effect rounded-3xl overflow-hidden shadow-md bg-white/90">
                        <div class="p-6 text-center py-8">
                            <div class="mx-auto w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-300 mb-4">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-700 mb-1">Belum Ada Tugas</h3>
                            <p class="text-sm text-gray-400">Tidak ada tugas aktif saat ini.</p>
                        </div>
                    </div>
                @endforelse

            </div>{{-- end col-span-1 --}}

            {{-- ── Riwayat Pengumpulan ──────────────────────────────────── --}}
            <div class="lg:col-span-2">
                <div class="glass-effect rounded-3xl overflow-hidden shadow-md flex flex-col">
                    {{-- header --}}
                    <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100/70 bg-white/80">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h2 class="text-base font-extrabold text-gray-900">Riwayat Pengumpulan</h2>
                    </div>

                    {{-- entries --}}
                    <div class="p-5 flex-1 bg-gray-50/40">
                        <div class="space-y-3">
                        @forelse($tasks as $task)
                            <a href="{{ $task->link }}" target="_blank" class="block group">
                                <div class="bg-white rounded-2xl border border-gray-100 hover:border-blue-200 hover:shadow-md transition-all p-4 flex items-center gap-4">
                                    {{-- info --}}
                                    <div class="flex-1 min-w-0">
                                        {{-- Nama Tugas --}}
                                        @if($task->assignedTask)
                                            <p class="text-xs font-bold text-blue-700 bg-blue-50 border border-blue-100 rounded-lg px-2.5 py-1 inline-block mb-2">
                                                📋 {{ $task->assignedTask->title }}
                                            </p>
                                        @endif
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <span class="badge bg-blue-100 text-blue-800">
                                                {{ \Carbon\Carbon::parse($task->submitted_at)->translatedFormat('l, d F Y') }}
                                            </span>
                                            <span class="text-xs text-gray-400 font-semibold">{{ \Carbon\Carbon::parse($task->submitted_at)->format('H:i') }} WIB</span>
                                        </div>
                                        <p class="text-xs text-blue-500 font-semibold truncate bg-blue-50 border border-blue-100 rounded-lg px-3 py-1.5 group-hover:text-blue-700 transition-colors">
                                            {{ $task->link }}
                                        </p>
                                    </div>
                                    {{-- arrow icon --}}
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 group-hover:bg-blue-600 flex items-center justify-center text-blue-500 group-hover:text-white transition-colors shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-16">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-100 text-gray-300 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <h3 class="text-base font-bold text-gray-500 mb-1">Riwayat Kosong</h3>
                                <p class="text-sm text-gray-400">Anda belum pernah mengumpulkan tugas.</p>
                            </div>
                        @endforelse
                        </div>

                        {{-- Pagination --}}
                        {{ $tasks->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
