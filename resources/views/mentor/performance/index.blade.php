<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-bold text-xl sm:text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                {{ __('Pelaporan & Monitoring Kinerja') }}
            </h2>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    Realtime Spreadsheet
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ── Hero Card: Profil & Jabatan ───────────────────────────── --}}
            <div class="relative overflow-hidden rounded-3xl shadow-lg"
                 style="background: linear-gradient(135deg, #15803d 0%, #166534 50%, #14532d 100%);">
                <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-white/10 pointer-events-none"></div>
                <div class="absolute -bottom-12 -left-12 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>

                <div class="relative z-10 p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center text-white shadow-lg shrink-0 text-2xl font-extrabold overflow-hidden">
                            {{ strtoupper(substr($mentorUser->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-400/20 text-yellow-300 border border-yellow-300/30">
                                    Account Representative Khusus
                                </span>
                            </div>
                            <h3 class="text-2xl sm:text-3xl font-extrabold text-white mt-1">{{ $mentorUser->name }}</h3>
                            <p class="text-green-100/80 text-sm mt-0.5">
                                Laporan Realisasi Target: <span class="font-bold text-yellow-200">{{ $allPerformance->date ?? 'Terbaru' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Stat Cards: Capaian Personal Mentor Ini ──────────────────── --}}
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Capaian Kinerja Anda</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    @foreach($myPerformance as $key => $item)
                        @php
                            $d = $item['data'];
                            $percentNum = $d ? (float) rtrim(str_replace(['%', ','], '', $d->percent), ' ') : 0;
                            $badgeClass = $percentNum >= 90 ? 'bg-green-100 text-green-800' : ($percentNum >= 50 ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800');
                        @endphp
                        <div class="glass-effect rounded-2xl p-5 card-hover border border-white/60 bg-white/80 flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ $item['title'] }}</p>
                                @if($d)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold {{ $badgeClass }}">
                                        {{ $d->percent }}
                                    </span>
                                @endif
                            </div>

                            <div class="mt-4">
                                @if($d)
                                    <div class="flex items-baseline gap-1">
                                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ $d->realisasi }}</p>
                                        <span class="text-xs text-gray-400 font-semibold">{{ $item['unit'] }}</span>
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-gray-100 text-xs space-y-1">
                                        <div class="flex justify-between text-gray-500">
                                            <span>Target:</span>
                                            <span class="font-bold text-gray-800">{{ $d->target }}</span>
                                        </div>
                                        <div class="flex justify-between text-gray-500">
                                            <span>GAP (Selisih):</span>
                                            <span class="font-bold {{ str_contains($d->gap, '-') || str_contains($d->gap, '(') ? 'text-red-500' : 'text-green-600' }}">
                                                {{ $d->gap }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-400 italic py-2">Data performa belum terhubung dengan nama akun Anda di spreadsheet.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Tabel Rincian & Komparasi Kinerja Seluruh Mentor ─────────── --}}
            <div class="glass-effect rounded-3xl overflow-hidden shadow-md" x-data="{ activeTab: 'tk_pu_aktif' }">
                <div class="px-6 sm:px-8 py-5 border-b border-gray-100 bg-white flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900">Rekapitulasi Capaian Seluruh Mentor</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Pemantauan realisasi target ARK secara real-time dari Google Spreadsheet.</p>
                    </div>

                    {{-- Tabs Navigation --}}
                    <div class="flex items-center gap-1 p-1 bg-gray-100 rounded-xl self-stretch md:self-auto overflow-x-auto">
                        @if(isset($allPerformance->categories))
                            @foreach($allPerformance->categories as $catKey => $cat)
                                <button type="button"
                                        @click="activeTab = '{{ $catKey }}'"
                                        :class="activeTab === '{{ $catKey }}' ? 'bg-white text-gray-900 shadow-sm font-bold' : 'text-gray-500 hover:text-gray-700 font-medium'"
                                        class="px-3.5 py-1.5 rounded-lg text-xs whitespace-nowrap transition-all">
                                    {{ $cat['title'] }}
                                </button>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="bg-white">
                    @if(isset($allPerformance->categories))
                        @foreach($allPerformance->categories as $catKey => $cat)
                            <div x-show="activeTab === '{{ $catKey }}'" style="display: none;">
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-[720px] text-left text-sm">
                                        <thead>
                                            <tr class="bg-gray-50/80 border-b border-gray-100 text-gray-500">
                                                <th class="px-6 sm:px-8 py-3.5 text-center w-14 font-bold text-xs uppercase tracking-wider">No</th>
                                                <th class="px-6 sm:px-8 py-3.5 font-bold text-xs uppercase tracking-wider">Nama Mentor (ARK)</th>
                                                <th class="px-6 sm:px-8 py-3.5 font-bold text-xs uppercase tracking-wider text-right">Target</th>
                                                <th class="px-6 sm:px-8 py-3.5 font-bold text-xs uppercase tracking-wider text-right">Realisasi</th>
                                                <th class="px-6 sm:px-8 py-3.5 font-bold text-xs uppercase tracking-wider text-right">GAP</th>
                                                <th class="px-6 sm:px-8 py-3.5 font-bold text-xs uppercase tracking-wider text-center">% Capaian</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            @forelse($cat['mentors'] as $mentor)
                                                @php
                                                    $isMe = strcasecmp(trim($mentor->name), trim($mentorUser->name)) === 0
                                                        || stripos(trim($mentor->name), trim($mentorUser->name)) !== false
                                                        || stripos(trim($mentorUser->name), trim($mentor->name)) !== false;
                                                    $percentNum = (float) rtrim(str_replace(['%', ','], '', $mentor->percent), ' ');
                                                    $badgeClass = $percentNum >= 90 ? 'bg-green-100 text-green-800' : ($percentNum >= 50 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700');
                                                @endphp
                                                <tr class="{{ $isMe ? 'bg-green-50/70 border-l-4 border-l-green-600' : 'hover:bg-gray-50/50' }} transition-colors">
                                                    <td class="px-6 sm:px-8 py-4 text-center font-semibold text-gray-400 text-xs">
                                                        {{ $mentor->no }}
                                                    </td>
                                                    <td class="px-6 sm:px-8 py-4">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-9 h-9 rounded-xl {{ $isMe ? 'bg-green-600 text-white font-extrabold' : 'bg-green-100 text-green-700 font-bold' }} flex items-center justify-center text-xs shrink-0 shadow-sm">
                                                                {{ strtoupper(substr($mentor->name, 0, 1)) }}
                                                            </div>
                                                            <div>
                                                                <div class="flex items-center gap-2">
                                                                    <p class="font-bold text-gray-900">{{ $mentor->name }}</p>
                                                                    @if($isMe)
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-green-600 text-white shadow-sm">
                                                                            Anda
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <p class="text-xs text-gray-400">Account Representative Khusus</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 sm:px-8 py-4 text-right text-gray-600 font-medium">
                                                        {{ $mentor->target }}
                                                    </td>
                                                    <td class="px-6 sm:px-8 py-4 text-right font-bold text-gray-900">
                                                        {{ $mentor->realisasi }}
                                                    </td>
                                                    <td class="px-6 sm:px-8 py-4 text-right font-medium {{ str_contains($mentor->gap, '-') || str_contains($mentor->gap, '(') ? 'text-red-500' : 'text-green-600' }}">
                                                        {{ $mentor->gap }}
                                                    </td>
                                                    <td class="px-6 sm:px-8 py-4 text-center">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $badgeClass }}">
                                                            {{ $mentor->percent }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="px-8 py-10 text-center text-gray-400">
                                                        Belum ada data mentor untuk kategori ini.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        @if($cat['total'])
                                            <tfoot class="bg-gray-50/90 font-bold border-t border-gray-200 text-gray-800">
                                                <tr>
                                                    <td colspan="2" class="px-6 sm:px-8 py-4 uppercase text-xs tracking-wider text-gray-700">
                                                        Total {{ $cat['title'] }}
                                                    </td>
                                                    <td class="px-6 sm:px-8 py-4 text-right text-gray-700">
                                                        {{ $cat['total']->target }}
                                                    </td>
                                                    <td class="px-6 sm:px-8 py-4 text-right text-gray-900 font-extrabold">
                                                        {{ $cat['total']->realisasi }}
                                                    </td>
                                                    <td class="px-6 sm:px-8 py-4 text-right {{ str_contains($cat['total']->gap, '-') || str_contains($cat['total']->gap, '(') ? 'text-red-500' : 'text-green-600' }}">
                                                        {{ $cat['total']->gap }}
                                                    </td>
                                                    <td class="px-6 sm:px-8 py-4 text-center">
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-green-600 text-white">
                                                            {{ $cat['total']->percent }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
