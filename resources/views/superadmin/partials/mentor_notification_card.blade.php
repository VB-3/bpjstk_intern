{{-- ══════════════════════════════════════════════════════════════════════════════
     CARD NOTIFIKASI PEMBARUAN DATA SPREADSHEET (DI ATAS RINGKASAN CAPAIAN)
══════════════════════════════════════════════════════════════════════════════ --}}
@php
    // Kumpulkan perubahan dari $changes (diff antar snapshot) dan $mutationLogs (log mutasi harian)
    $activeChanges = $changes ?? [];

    if (empty($activeChanges) && !empty($snapshot) && !empty($previousSnapshot)) {
        $currCats = is_array($snapshot->categories) ? $snapshot->categories : json_decode($snapshot->categories, true);
        $prevCats = is_array($previousSnapshot->categories) ? $previousSnapshot->categories : json_decode($previousSnapshot->categories, true);
        $activeChanges = \App\Models\MentorPerformanceSnapshot::detectCategoryDifferences($prevCats ?? [], $currCats ?? []);
    }

    $allMutationLogs = $mutationLogs ?? ($snapshot ? $snapshot->getMutationLogs() : []);
@endphp

<div class="rounded-2xl overflow-hidden shadow-md border {{ count($activeChanges) > 0 || count($allMutationLogs) > 0 ? 'border-emerald-400 ring-2 ring-emerald-500/20' : 'border-green-200' }} bg-white transition-all">
    {{-- ── Card Header ── --}}
    <div class="px-5 py-4 {{ count($activeChanges) > 0 || count($allMutationLogs) > 0 ? 'bg-gradient-to-r from-emerald-700 via-green-700 to-emerald-800' : 'bg-gradient-to-r from-green-800 via-emerald-800 to-green-900' }} text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center text-yellow-300 shrink-0 shadow-inner">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h4 class="font-extrabold text-white text-sm sm:text-base leading-tight">Notifikasi Pembaruan Data Spreadsheet</h4>
                    @if(count($activeChanges) > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-yellow-400 text-green-950 shadow-sm animate-pulse">
                            ⚠️ {{ count($activeChanges) }} Mutasi Mentor Terdeteksi
                        </span>
                    @elseif(count($allMutationLogs) > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-400/30 text-yellow-200 border border-yellow-300/30">
                            Riwayat Mutasi Hari Ini
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/20 text-green-100 border border-white/20">
                            Sinkronisasi Aktif
                        </span>
                    @endif
                </div>
                <p class="text-green-100/90 text-xs mt-0.5">
                    Memonitor secara otomatis penambahan target, peningkatan realisasi, dan progres berkala mentor dari Google Spreadsheet.
                </p>
            </div>
        </div>

        <div class="shrink-0 flex items-center gap-2 self-start sm:self-center">
            <span class="text-[11px] text-green-200/90 bg-black/25 px-3 py-1.5 rounded-xl border border-white/10 font-bold flex items-center gap-1.5 shadow-inner">
                <span>📅</span>
                <span>{{ \Carbon\Carbon::parse($selectedDate ?? now())->translatedFormat('d M Y') }}</span>
            </span>
        </div>
    </div>

    {{-- ── Card Content ── --}}
    @if(count($activeChanges) > 0)
        {{-- Banner Catatan Mutasi Terdeteksi --}}
        <div class="p-4 bg-emerald-50/80 border-b border-emerald-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-emerald-950">
            <div class="flex items-center gap-2 font-bold">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-ping"></span>
                <span>Catatan Mutasi Mentor Terdeteksi dari Google Spreadsheet:</span>
            </div>
            <div class="text-emerald-800 text-[11px] font-medium">
                Dibandingkan dengan: <strong>{{ $previousSnapshot ? $previousSnapshot->snapshot_date->translatedFormat('d F Y') : 'Snapshot Baseline Sebelumnya' }}</strong>
            </div>
        </div>

        {{-- Daftar Mentor yang Mengalami Perubahan --}}
        <div class="divide-y divide-gray-100">
            @foreach($activeChanges as $ch)
                @php
                    $diffR = (int) ($ch['diffReal'] ?? 0);
                    $diffT = (int) ($ch['diffTarget'] ?? 0);
                    $rUp = $diffR > 0;
                    $rDn = $diffR < 0;
                    $tUp = $diffT > 0;
                    $tDn = $diffT < 0;
                @endphp
                <div class="p-4 sm:p-5 hover:bg-gray-50/90 transition-colors flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    {{-- Info Mentor --}}
                    <div class="flex items-start sm:items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl {{ $rUp ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : ($rDn ? 'bg-red-100 text-red-800 border border-red-300' : 'bg-blue-100 text-blue-800 border border-blue-300') }} flex items-center justify-center font-extrabold text-base shadow-sm shrink-0">
                            {{ strtoupper(substr($ch['name'] ?? 'M', 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h5 class="font-extrabold text-gray-900 text-sm sm:text-base">{{ $ch['name'] ?? 'Nama Mentor' }}</h5>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-green-100 text-green-800 border border-green-200">
                                    {{ $ch['category'] ?? 'Kategori' }}
                                </span>
                                @if(!empty($ch['is_new']))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold bg-blue-100 text-blue-800">
                                        Mentor Baru
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">Account Representative Khusus (ARK)</p>
                        </div>
                    </div>

                    {{-- Badges Perubahan Target & Realisasi --}}
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        {{-- Realisasi --}}
                        @if($diffR !== 0)
                            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl {{ $rUp ? 'bg-emerald-50 border border-emerald-300 text-emerald-950 font-medium' : 'bg-red-50 border border-red-300 text-red-950 font-medium' }} shadow-sm">
                                <span class="font-extrabold {{ $rUp ? 'text-emerald-700' : 'text-red-600' }}">
                                    {{ $rUp ? '📈 Realisasi Bertambah:' : '📉 Realisasi Menurun:' }}
                                </span>
                                <span class="text-gray-500 font-bold line-through">{{ $ch['prevReal'] ?? '0' }}</span>
                                <span class="text-gray-400 font-bold">➜</span>
                                <span class="font-black {{ $rUp ? 'text-emerald-800 text-sm' : 'text-red-700 text-sm' }}">{{ $ch['currReal'] ?? '0' }}</span>
                                <span class="font-black {{ $rUp ? 'text-emerald-700' : 'text-red-600' }}">
                                    ({{ $rUp ? '+' : '' }}{{ $diffR }})
                                </span>
                            </div>
                        @else
                            <div class="flex items-center gap-1 px-2.5 py-1 rounded-xl bg-gray-100 text-gray-600 font-medium text-[11px]">
                                <span>Realisasi Tetap:</span>
                                <span class="font-bold text-gray-800">{{ $ch['currReal'] ?? '0' }}</span>
                            </div>
                        @endif

                        {{-- Target --}}
                        @if($diffT !== 0)
                            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl {{ $tUp ? 'bg-blue-50 border border-blue-300 text-blue-950 font-medium' : 'bg-orange-50 border border-orange-300 text-orange-950 font-medium' }} shadow-sm">
                                <span class="font-extrabold {{ $tUp ? 'text-blue-700' : 'text-orange-600' }}">
                                    {{ $tUp ? '🎯 Target Bertambah:' : '🎯 Target Berubah:' }}
                                </span>
                                <span class="text-gray-500 font-bold line-through">{{ $ch['prevTarget'] ?? '0' }}</span>
                                <span class="text-gray-400 font-bold">➜</span>
                                <span class="font-black {{ $tUp ? 'text-blue-800 text-sm' : 'text-orange-700 text-sm' }}">{{ $ch['currTarget'] ?? '0' }}</span>
                                <span class="font-black {{ $tUp ? 'text-blue-700' : 'text-orange-600' }}">
                                    ({{ $tUp ? '+' : '' }}{{ $diffT }})
                                </span>
                            </div>
                        @else
                            <div class="flex items-center gap-1 px-2.5 py-1 rounded-xl bg-gray-100 text-gray-600 font-medium text-[11px]">
                                <span>Target:</span>
                                <span class="font-bold text-gray-800">{{ $ch['currTarget'] ?? '0' }}</span>
                            </div>
                        @endif

                        {{-- % Capaian --}}
                        @if(($ch['prevPercent'] ?? '') !== ($ch['currPercent'] ?? ''))
                            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 border border-purple-300 text-purple-950 font-medium shadow-sm">
                                <span class="font-extrabold text-purple-700">📊 Capaian:</span>
                                <span class="text-gray-500 font-bold line-through">{{ $ch['prevPercent'] ?? '0%' }}</span>
                                <span class="text-gray-400 font-bold">➜</span>
                                <span class="font-black text-purple-800 text-sm">{{ $ch['currPercent'] ?? '0%' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Footer Card Mutasi --}}
        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-gray-500">
            <span class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Sistem otomatis merekam snapshot setiap pembaruan Google Spreadsheet terdeteksi.
            </span>
            <span class="font-extrabold text-emerald-800">
                Total {{ count($activeChanges) }} mentor mengalami perubahan data
            </span>
        </div>

    @elseif(count($allMutationLogs) > 0)
        {{-- Terdapat riwayat log mutasi pada hari ini --}}
        <div class="p-4 bg-emerald-50/60 border-b border-emerald-100 text-xs font-bold text-emerald-900 flex items-center justify-between">
            <span>Riwayat Perubahan Data Spreadsheet yang Tercatat Hari Ini:</span>
            <span class="text-[11px] font-semibold text-emerald-700">{{ count($allMutationLogs) }} sesi pembaruan</span>
        </div>

        <div class="divide-y divide-gray-100">
            @foreach($allMutationLogs as $log)
                <div class="p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold text-[11px]">
                            ⏰ {{ $log['time'] ?? 'Pembaruan' }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $log['date'] ?? '' }}</span>
                    </div>
                    <div class="space-y-1.5 pl-3 border-l-2 border-emerald-400">
                        @foreach($log['changes'] ?? [] as $item)
                            <div class="text-xs text-gray-800 flex flex-wrap items-center gap-2">
                                <span class="font-bold text-gray-900">{{ $item['name'] }}</span>
                                <span class="text-[11px] px-1.5 py-0.5 bg-gray-100 rounded text-gray-600 font-semibold">{{ $item['category'] }}</span>
                                <span>:</span>
                                @if(!empty($item['diffTarget']))
                                    <span class="text-blue-700 font-bold">Target {{ $item['prevTarget'] }} ➜ {{ $item['currTarget'] }} ({{ $item['diffTarget'] > 0 ? '+' : '' }}{{ $item['diffTarget'] }})</span>
                                @endif
                                @if(!empty($item['diffReal']))
                                    <span class="text-emerald-700 font-bold">Realisasi {{ $item['prevReal'] }} ➜ {{ $item['currReal'] }} ({{ $item['diffReal'] > 0 ? '+' : '' }}{{ $item['diffReal'] }})</span>
                                @endif
                                <span class="text-purple-700 font-bold">Capaian {{ $item['currPercent'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

    @else
        {{-- Kondisi Tidak Ada Perubahan / Standby Aktif --}}
        <div class="p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-700 border border-green-200 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-gray-900">Seluruh Data Mentor Telah Sinkron dengan Google Spreadsheet</h5>
                    <p class="text-xs text-gray-500 mt-0.5 max-w-2xl leading-relaxed">
                        Data target dan realisasi mentor pada snapshot ini stabil. Setiap kali terjadi pembaruan di spreadsheet (seperti mentor <strong>Fariza Septian Nasution</strong> atau mentor lainnya yang menambah target atau mencatatkan kenaikan realisasi), rincian mutasinya akan langsung terdeteksi otomatis dan tercatat secara transparan pada kartu ini.
                    </p>
                </div>
            </div>

            <div class="shrink-0 flex items-center gap-2 self-end md:self-center">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold border border-gray-200">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    Live Connected
                </span>
            </div>
        </div>
    @endif
</div>
