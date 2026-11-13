{{-- ══════════════════════════════════════════════════════════════════════════════
     CARD NOTIFIKASI PEMBARUAN DATA SPREADSHEET (DI ATAS RINGKASAN CAPAIAN)
══════════════════════════════════════════════════════════════════════════════ --}}
@php
    $changes = [];
    if (!empty($snapshot) && !empty($previousSnapshot)) {
        $currCats = is_array($snapshot->categories) ? $snapshot->categories : json_decode($snapshot->categories, true);
        $prevCats = is_array($previousSnapshot->categories) ? $previousSnapshot->categories : json_decode($previousSnapshot->categories, true);

        if (is_array($currCats) && is_array($prevCats)) {
            foreach ($currCats as $catKey => $currCat) {
                $prevCat  = $prevCats[$catKey] ?? null;
                $catTitle = $currCat['title'] ?? $catKey;

                $prevMentorMap = [];
                if ($prevCat && !empty($prevCat['mentors'])) {
                    foreach ($prevCat['mentors'] as $pm) {
                        $pName = strtolower(trim($pm['name'] ?? ''));
                        if ($pName !== '') {
                            $prevMentorMap[$pName] = $pm;
                        }
                    }
                }

                if (!empty($currCat['mentors'])) {
                    foreach ($currCat['mentors'] as $cm) {
                        $cName = strtolower(trim($cm['name'] ?? ''));
                        $pm = $prevMentorMap[$cName] ?? null;
                        if (!$pm) continue;

                        $cTarget = (int) preg_replace('/[^\d-]/', '', (string)($cm['target'] ?? '0'));
                        $pTarget = (int) preg_replace('/[^\d-]/', '', (string)($pm['target'] ?? '0'));
                        $cReal   = (int) preg_replace('/[^\d-]/', '', (string)($cm['realisasi'] ?? '0'));
                        $pReal   = (int) preg_replace('/[^\d-]/', '', (string)($pm['realisasi'] ?? '0'));

                        $diffTarget = $cTarget - $pTarget;
                        $diffReal   = $cReal - $pReal;

                        if ($diffTarget !== 0 || $diffReal !== 0 || ($cm['percent'] ?? '') !== ($pm['percent'] ?? '')) {
                            $changes[] = [
                                'name'          => $cm['name'] ?? '',
                                'category'      => $catTitle,
                                'diffTarget'    => $diffTarget,
                                'diffReal'      => $diffReal,
                                'prevTarget'    => $pm['target'] ?? '0',
                                'currTarget'    => $cm['target'] ?? '0',
                                'prevReal'      => $pm['realisasi'] ?? '0',
                                'currReal'      => $cm['realisasi'] ?? '0',
                                'prevPercent'   => $pm['percent'] ?? '0%',
                                'currPercent'   => $cm['percent'] ?? '0%',
                            ];
                        }
                    }
                }
            }
        }
    }
@endphp

<div class="rounded-2xl overflow-hidden shadow-md border {{ count($changes) > 0 ? 'border-emerald-300' : 'border-green-200' }} bg-white">
    {{-- ── Card Header ── --}}
    <div class="px-5 py-3.5 {{ count($changes) > 0 ? 'bg-gradient-to-r from-emerald-700 via-green-700 to-emerald-800' : 'bg-gradient-to-r from-green-800 via-emerald-800 to-green-900' }} text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center text-yellow-300 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h4 class="font-extrabold text-white text-sm sm:text-base leading-tight">Notifikasi Pembaruan Data Spreadsheet</h4>
                    @if(count($changes) > 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-400 text-green-950 animate-pulse">
                            {{ count($changes) }} Perubahan Baru
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/20 text-green-100 border border-white/20">
                            Sinkronisasi Aktif
                        </span>
                    @endif
                </div>
                <p class="text-green-100/90 text-xs mt-0.5">
                    Memonitor penambahan target, peningkatan realisasi, dan progres berkala mentor dari Google Spreadsheet.
                </p>
            </div>
        </div>

        <div class="shrink-0 flex items-center gap-2">
            <span class="text-[11px] text-green-200/90 bg-black/20 px-3 py-1 rounded-xl border border-white/10 font-semibold">
                📅 {{ \Carbon\Carbon::parse($selectedDate ?? now())->translatedFormat('d M Y') }}
            </span>
        </div>
    </div>

    {{-- ── Card Content ── --}}
    @if(count($changes) > 0)
        {{-- List Mentor Yang Mengalami Perubahan --}}
        <div class="p-4 bg-emerald-50/40 border-b border-emerald-100 flex items-center justify-between text-xs text-emerald-900">
            <span class="font-bold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                Daftar Mutasi Target & Realisasi Mentor Terdeteksi:
            </span>
            <span class="text-emerald-700 text-[11px]">
                Pembanding snapshot: <strong>{{ $previousSnapshot ? $previousSnapshot->snapshot_date->translatedFormat('d F Y') : 'Snapshot Sebelumnya' }}</strong>
            </span>
        </div>

        <div class="divide-y divide-gray-100">
            @foreach($changes as $ch)
                @php
                    $rUp = $ch['diffReal'] > 0;
                    $rDn = $ch['diffReal'] < 0;
                    $tUp = $ch['diffTarget'] > 0;
                    $tDn = $ch['diffTarget'] < 0;
                @endphp
                <div class="p-4 hover:bg-gray-50/80 transition-colors flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl {{ $rUp ? 'bg-emerald-100 text-emerald-800' : ($rDn ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }} flex items-center justify-center font-extrabold text-sm shadow-sm shrink-0">
                            {{ strtoupper(substr($ch['name'], 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="font-bold text-gray-900 text-sm">{{ $ch['name'] }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800">
                                    {{ $ch['category'] }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-400">Account Representative Khusus (ARK)</p>
                        </div>
                    </div>

                    {{-- Badges Perubahan Target & Realisasi --}}
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        {{-- Realisasi --}}
                        @if($ch['diffReal'] !== 0)
                            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl {{ $rUp ? 'bg-emerald-50 border border-emerald-200 text-emerald-900' : 'bg-red-50 border border-red-200 text-red-900' }}">
                                <span class="font-bold {{ $rUp ? 'text-emerald-700' : 'text-red-600' }}">
                                    {{ $rUp ? '📈 Realisasi Bertambah:' : '📉 Realisasi Menurun:' }}
                                </span>
                                <span class="text-gray-500 font-medium">{{ $ch['prevReal'] }}</span>
                                <span class="text-gray-400">➜</span>
                                <span class="font-extrabold {{ $rUp ? 'text-emerald-800' : 'text-red-700' }}">{{ $ch['currReal'] }}</span>
                                <span class="font-extrabold {{ $rUp ? 'text-emerald-600' : 'text-red-600' }}">
                                    ({{ $rUp ? '+' : '' }}{{ $ch['diffReal'] }})
                                </span>
                            </div>
                        @endif

                        {{-- Target --}}
                        @if($ch['diffTarget'] !== 0)
                            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl {{ $tUp ? 'bg-blue-50 border border-blue-200 text-blue-900' : 'bg-orange-50 border border-orange-200 text-orange-900' }}">
                                <span class="font-bold {{ $tUp ? 'text-blue-700' : 'text-orange-600' }}">
                                    {{ $tUp ? '🎯 Target Bertambah:' : '🎯 Target Berubah:' }}
                                </span>
                                <span class="text-gray-500 font-medium">{{ $ch['prevTarget'] }}</span>
                                <span class="text-gray-400">➜</span>
                                <span class="font-extrabold {{ $tUp ? 'text-blue-800' : 'text-orange-700' }}">{{ $ch['currTarget'] }}</span>
                                <span class="font-extrabold {{ $tUp ? 'text-blue-600' : 'text-orange-600' }}">
                                    ({{ $tUp ? '+' : '' }}{{ $ch['diffTarget'] }})
                                </span>
                            </div>
                        @endif

                        {{-- % Capaian --}}
                        @if($ch['prevPercent'] !== $ch['currPercent'])
                            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 border border-purple-200 text-purple-900">
                                <span class="font-bold text-purple-700">% Capaian:</span>
                                <span class="text-gray-500">{{ $ch['prevPercent'] }}</span>
                                <span class="text-gray-400">➜</span>
                                <span class="font-extrabold text-purple-800">{{ $ch['currPercent'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="px-5 py-2.5 bg-gray-50 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
            <span>Snapshot tersimpan otomatis per 24 jam.</span>
            <span class="font-bold text-emerald-700">Total {{ count($changes) }} mentor mengalami mutasi data</span>
        </div>
    @else
        {{-- Kondisi Tidak Ada Perubahan / Standby Aktif --}}
        <div class="p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-700 border border-green-200 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-gray-900">Seluruh Data Mentor Telah Sinkron dengan Google Spreadsheet</h5>
                    <p class="text-xs text-gray-500 mt-0.5 max-w-2xl leading-relaxed">
                        Data target dan realisasi mentor saat ini stabil. Setiap kali terjadi pembaruan di spreadsheet (seperti mentor <strong>Fariza Septian Nasution</strong> atau mentor lainnya yang menambah target atau mencatatkan kenaikan realisasi), rincian mutasinya akan langsung terdeteksi dan tercatat secara transparan pada kartu ini.
                    </p>
                </div>
            </div>

            <div class="shrink-0 flex items-center gap-2 self-end md:self-center">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gray-100 text-gray-700 text-xs font-semibold border border-gray-200">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    Live Connected
                </span>
            </div>
        </div>
    @endif
</div>
