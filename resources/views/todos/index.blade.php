<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            To-Do List Harian
        </h2>
    </x-slot>

    <div class="page-wrapper">

        {{-- ── BPU Stat Banner ──────────────────────────────────────────── --}}
        <div class="glass-effect rounded-2xl p-5 flex items-center gap-5 border border-white/60">
            <div class="stat-icon bg-blue-50 text-blue-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-0.5">Total Akumulasi Peserta BPU Saya</p>
                <p class="text-3xl font-extrabold text-gray-900 leading-none">{{ $totalBpu }} <span class="text-base font-medium text-gray-400">Orang</span></p>
            </div>
        </div>

        {{-- ── Main Grid ────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            {{-- ── Form Card (sticky) ───────────────────────────────────── --}}
            <div class="lg:col-span-1 lg:sticky top-24">
                <div class="glass-effect rounded-3xl overflow-hidden shadow-md">
                    {{-- gradient header --}}
                    <div class="relative overflow-hidden px-6 py-6"
                         style="background: linear-gradient(135deg,#16a34a 0%,#166534 100%)">
                        <div class="absolute -top-8 -right-8 w-28 h-28 rounded-full bg-white/10"></div>
                        <div class="flex items-center gap-3 relative z-10">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-extrabold text-white">Laporan Harian</h2>
                                <p class="text-green-200 text-xs mt-0.5">Catat kegiatan yang Anda lakukan</p>
                            </div>
                        </div>
                    </div>

                    {{-- form body --}}
                    <div class="p-6 bg-white/90">
                        @if(!auth()->user()->gdrive_link)
                            {{-- needs gdrive --}}
                            <div class="mb-5 p-4 bg-amber-50 border border-amber-200 rounded-2xl">
                                <p class="text-sm text-amber-800 font-semibold leading-relaxed">
                                    ⚠️ Sebelum mengisi kegiatan, tautkan link Google Drive Anda (sekali saja).
                                </p>
                            </div>
                            <form method="POST" action="{{ route('todos.gdrive') }}" class="space-y-4"
                                  x-data="{ loading: false }" @submit="loading = true">
                                @csrf
                                <div>
                                    <label for="gdrive_link" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Link Google Drive</label>
                                    <input id="gdrive_link" name="gdrive_link" type="url" required placeholder="https://drive.google.com/..."
                                        class="w-full border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-2.5 text-sm font-medium transition-all outline-none">
                                </div>
                                <button type="submit" x-bind:disabled="loading"
                                    class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm text-white shadow-md shadow-green-200 disabled:opacity-70 transition-all"
                                    style="background:linear-gradient(135deg,#22c55e,#16a34a)">
                                    <span x-show="!loading">Simpan Link Drive</span>
                                    <span x-show="loading" class="flex items-center gap-2">
                                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                        Menyimpan...
                                    </span>
                                </button>
                            </form>

                        @elseif(!$todayTodo)
                            <form method="POST" action="{{ route('todos.store') }}" class="space-y-5"
                                  x-data="{ loading: false }" @submit="loading = true">
                                @csrf
                                <div>
                                    <label for="summary" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Deskripsi Kegiatan</label>
                                    <textarea id="summary" name="summary" rows="5" required
                                        placeholder="Tuliskan poin-poin kegiatan hari ini..."
                                        class="w-full border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-3 text-sm font-medium transition-all outline-none resize-none"></textarea>
                                    <p class="mt-1.5 text-xs text-gray-400">Dokumentasi foto unggah ke Google Drive Anda.</p>
                                </div>
                                <div>
                                    <label for="bpu_achievement" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Pencapaian BPU Harian</label>
                                    <input id="bpu_achievement" name="bpu_achievement" type="number" min="0" value="0" required
                                        class="w-full border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-2.5 text-sm font-medium transition-all outline-none">
                                    <p class="mt-1.5 text-xs text-gray-400">Isi 0 jika tidak ada pencapaian BPU.</p>
                                </div>
                                <button type="submit" x-bind:disabled="loading"
                                    class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm text-white shadow-md shadow-green-200 disabled:opacity-70 transition-all"
                                    style="background:linear-gradient(135deg,#22c55e,#16a34a)">
                                    <span x-show="!loading">Simpan Kegiatan</span>
                                    <span x-show="loading" class="flex items-center gap-2">
                                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                        Mengirim...
                                    </span>
                                </button>
                            </form>

                        @else
                            {{-- already submitted today --}}
                            <div class="text-center pt-2 pb-4">
                                <div class="mx-auto w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center text-green-600 mb-3">
                                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <h3 class="text-base font-extrabold text-gray-900 mb-1">Sudah Terisi!</h3>
                                <p class="text-xs text-gray-500 leading-relaxed">Rangkuman kegiatan hari ini sudah dikirim. Anda dapat melakukan Check-out.</p>
                            </div>

                            <div class="mt-4 pt-5 border-t border-gray-100">
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Update Pencapaian BPU</label>
                                <form method="POST" action="{{ route('todos.updateBpu', $todayTodo) }}" class="flex gap-3">
                                    @csrf
                                    @method('PUT')
                                    <input name="bpu_achievement" type="number" min="0" value="{{ $todayTodo->bpu_achievement }}"
                                        class="flex-1 border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl px-4 py-2.5 text-sm font-medium outline-none">
                                    <button type="submit"
                                        class="px-4 py-2.5 rounded-xl bg-green-100 hover:bg-green-200 text-green-800 text-sm font-bold transition-colors">
                                        Update
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── History List ─────────────────────────────────────────── --}}
            <div class="lg:col-span-2">
                <div class="glass-effect rounded-3xl overflow-hidden shadow-md flex flex-col">
                    {{-- header + filter --}}
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 px-6 py-5 border-b border-gray-100/70 bg-white/80">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-9 h-9 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            </div>
                            <h2 class="text-base font-extrabold text-gray-900">Riwayat Kegiatan</h2>
                        </div>
                        {{-- date filter --}}
                        <form method="GET" action="{{ route('todos.index') }}" class="flex items-center gap-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                                    class="pl-9 pr-3 py-2 border border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 rounded-xl text-sm font-medium outline-none cursor-pointer">
                            </div>
                            @if(request()->filled('date'))
                                <a href="{{ route('todos.index') }}"
                                   class="p-2 text-gray-400 hover:text-red-500 bg-gray-50 hover:bg-red-50 border border-gray-200 rounded-xl transition-colors" title="Hapus Filter">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                        </form>
                    </div>

                    {{-- entries --}}
                    <div class="p-5 flex-1 bg-gray-50/40">
                        <div class="space-y-3">
                        @forelse($todos as $todo)
                            <div class="bg-white rounded-2xl border border-gray-100 hover:border-green-200 hover:shadow-md transition-all p-5">
                                {{-- tags --}}
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="badge bg-green-100 text-green-800">
                                        {{ \Carbon\Carbon::parse($todo->date)->translatedFormat('l, d F Y') }}
                                    </span>
                                    <span class="badge bg-blue-100 text-blue-800 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        BPU: {{ $todo->bpu_achievement }}
                                    </span>
                                </div>
                                {{-- content --}}
                                <div class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed border-l-4 border-green-200 pl-3">{{ $todo->summary }}</div>
                            </div>
                        @empty
                            <div class="text-center py-16">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-100 text-gray-300 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <h3 class="text-base font-bold text-gray-500 mb-1">Riwayat Kosong</h3>
                                @if(request()->filled('date'))
                                    <p class="text-sm text-gray-400">Tidak ada catatan pada {{ \Carbon\Carbon::parse(request('date'))->translatedFormat('d F Y') }}.</p>
                                @else
                                    <p class="text-sm text-gray-400">Belum ada catatan kegiatan yang ditambahkan.</p>
                                @endif
                            </div>
                        @endforelse
                        </div>

                        {{-- Pagination --}}
                        {{ $todos->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>


