<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Manajemen Tugas') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">


            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Form Buat Tugas -->
                <div class="lg:col-span-1">
                    <div class="glass-effect rounded-3xl overflow-hidden shadow-lg border border-white/50 sticky top-24 h-auto flex flex-col">
                        <div class="bg-gradient-to-br from-green-500 to-green-700 p-6 sm:p-8 text-white">
                            <h2 class="text-xl font-extrabold flex items-center gap-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Buat Tugas Baru
                            </h2>
                            <p class="mt-2 text-green-100 text-sm">Tugas yang dikirim akan menunggu persetujuan dari Super Admin sebelum diaktifkan.</p>
                        </div>
                        
                        <div class="p-6 sm:p-8 bg-white/80 flex-1 flex flex-col justify-center">
                            <form method="POST" action="{{ route('mentor.tasks.store') }}" class="space-y-6">
                                @csrf
                                <div>
                                    <x-input-label for="title" :value="__('Judul Tugas')" class="font-bold text-gray-700" />
                                    <x-text-input id="title" name="title" type="text" class="border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm block w-full mt-2 transition-colors" required placeholder="Cth: Membuat Laporan Keuangan" />
                                </div>

                                <div>
                                    <x-input-label for="description" :value="__('Deskripsi Singkat')" class="font-bold text-gray-700" />
                                    <textarea id="description" name="description" rows="3" class="border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm block w-full mt-2 transition-colors" placeholder="Deskripsikan apa yang harus dikerjakan peserta..."></textarea>
                                </div>

                                <div>
                                    <x-input-label for="deadline" :value="__('Batas Waktu (Deadline)')" class="font-bold text-gray-700" />
                                    <x-text-input id="deadline" name="deadline" type="datetime-local" class="border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm block w-full mt-2 transition-colors" required />
                                </div>
                                
                                <div class="pt-2">
                                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-md shadow-green-200 text-sm font-bold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                        Kirim untuk Disetujui
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Tugas -->
                <div class="lg:col-span-2">
                    <div class="glass-effect rounded-3xl overflow-hidden shadow-lg border border-white/50 h-full flex flex-col">
                        <div class="bg-white p-6 sm:px-8 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <h2 class="text-xl font-extrabold text-gray-900">Riwayat Tugas yang Dibuat</h2>
                        </div>
                        
                        <div class="p-6 sm:p-8 flex-1 bg-gray-50/50">
                            <div class="space-y-4">
                                @forelse($tasks as $task)
                                    @php
                                        $isPastDeadline = $task->deadline && \Carbon\Carbon::parse($task->deadline)->isPast();
                                        $isLive = ($task->status === 'approved' || $task->is_active) && !$isPastDeadline;
                                    @endphp
                                    <div class="p-5 bg-white rounded-2xl border {{ $isLive ? 'border-green-400 shadow-md ring-1 ring-green-400' : 'border-gray-200 shadow-sm' }} transition-all flex items-center justify-between gap-4 relative overflow-hidden">
                                        
                                        @if($isPastDeadline)
                                            <div class="absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg uppercase tracking-wider">Selesai</div>
                                        @elseif($isLive)
                                            <div class="absolute top-0 right-0 bg-green-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg uppercase tracking-wider">Aktif</div>
                                        @endif

                                        <div class="flex-1 min-w-0 pr-4 mt-2">
                                            <h3 class="text-lg font-bold text-gray-900 truncate">{{ $task->title }}</h3>
                                            <p class="text-sm text-gray-500 line-clamp-2 mt-1">{{ $task->description }}</p>
                                            
                                            <div class="flex items-center gap-4 mt-3">
                                                <span class="inline-flex items-center text-xs font-bold text-gray-500">
                                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    Dibuat: {{ \Carbon\Carbon::parse($task->created_at)->format('d/m/Y') }}
                                                </span>
                                                <span class="inline-flex items-center text-xs font-bold text-red-600">
                                                    <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Tenggat: {{ \Carbon\Carbon::parse($task->deadline)->format('d/m/Y H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="shrink-0 flex items-center gap-2">
                                            @if($task->status === 'pending')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Menunggu Persetujuan
                                                </span>
                                            @elseif($task->status === 'approved')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    Disetujui
                                                </span>
                                            @elseif($task->status === 'rejected')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    Ditolak
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-16">
                                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white shadow-sm text-gray-400 mb-4 border border-gray-100">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Tugas</h3>
                                        <p class="text-gray-500">Anda belum pernah membuat tugas untuk peserta.</p>
                                    </div>
                                 @endforelse
                            </div>
                        </div>
                        {{-- Pagination Tugas --}}
                        <div class="px-6 pb-6">
                            {{ $tasks->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Riwayat Pengumpulan Tugas Oleh Peserta -->
            <div class="glass-effect rounded-3xl overflow-hidden shadow-lg border border-white/50 mt-8">
                <div class="bg-white p-6 sm:px-8 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-900">Pengumpulan Tugas oleh Peserta</h2>
                        <p class="text-sm text-gray-500 mt-1">Daftar semua tugas yang telah dikumpulkan (disubmit) oleh anak didik.</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-left text-sm">
                        <thead class="bg-gray-50/50 text-gray-600 font-bold border-b border-gray-100">
                            <tr>
                                <th class="px-8 py-4">Nama Peserta</th>
                                <th class="px-8 py-4">Tautan/Link Pengumpulan</th>
                                <th class="px-8 py-4 text-center">Waktu Submit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($submissions as $submission)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                                                {{ substr($submission->user->name ?? 'U', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $submission->user->name ?? 'Unknown' }}</p>
                                                <p class="text-xs text-gray-500">{{ $submission->user->email ?? '' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <a href="{{ $submission->link }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-800 transition-colors font-medium border border-blue-100/50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            Buka Tautan
                                        </a>
                                        <p class="text-xs text-gray-400 mt-1 truncate max-w-xs">{{ $submission->link }}</p>
                                    </td>
                                    <td class="px-8 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
                                            {{ \Carbon\Carbon::parse($submission->submitted_at)->translatedFormat('d M Y, H:i') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-10 text-center text-gray-500">
                                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-3 border border-gray-100">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                        <p>Belum ada pengumpulan tugas dari peserta mana pun.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Pagination Submissions --}}
                <div class="px-6 pb-6 pt-2">
                    {{ $submissions->links('vendor.pagination.custom') }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>


