<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentorPerformanceSnapshot extends Model
{
    protected $fillable = [
        'snapshot_date',
        'header_date',
        'categories',
        'checksum',
        'has_changed',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'categories'    => 'array',
        'has_changed'   => 'boolean',
    ];

    /**
     * Merekam / memperbarui snapshot harian dari data Google Spreadsheet.
     * Mengelola deteksi mutasi real-time dan memastikan snapshot baseline kemarin tersedia.
     */
    public static function recordCurrentSnapshot($mentorPerformance): ?self
    {
        if (empty($mentorPerformance->categories)) {
            return null;
        }

        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        // Serialisasi kategori dari objek spreadsheet
        $serializedCategories = [];
        $validKeys = ['tk_pu_aktif', 'bpu_aktif', 'iuran'];

        foreach ($validKeys as $key) {
            $cat = $mentorPerformance->categories[$key] ?? null;
            if (!$cat) continue;

            $mentors = [];
            $rawMentors = $cat['mentors'] ?? [];
            foreach ($rawMentors as $m) {
                $mentors[] = [
                    'no'        => (int) ($m->no ?? 0),
                    'name'      => (string) ($m->name ?? ''),
                    'target'    => (string) ($m->target ?? '0'),
                    'realisasi' => (string) ($m->realisasi ?? '0'),
                    'gap'       => (string) ($m->gap ?? '0'),
                    'percent'   => (string) ($m->percent ?? '0%'),
                ];
            }

            $total = null;
            if (!empty($cat['total'])) {
                $total = [
                    'target'    => (string) ($cat['total']->target ?? '0'),
                    'realisasi' => (string) ($cat['total']->realisasi ?? '0'),
                    'gap'       => (string) ($cat['total']->gap ?? '0'),
                    'percent'   => (string) ($cat['total']->percent ?? '0%'),
                ];
            }

            $serializedCategories[$key] = [
                'title'   => (string) ($cat['title'] ?? ''),
                'unit'    => (string) ($cat['unit'] ?? ''),
                'mentors' => $mentors,
                'total'   => $total,
            ];
        }

        // Hitung checksum murni dari data kategori (tanpa metadata internal)
        $newChecksum = md5(json_encode($serializedCategories));

        // Cek snapshot hari ini di database
        $existingToday = self::where('snapshot_date', $today)->first();

        // Cek snapshot kemarin di database
        $yesterdaySnapshot = self::where('snapshot_date', $yesterday)->first();

        // Kasus 1: Belum ada snapshot sama sekali di database (Inisialisasi pertama kali)
        if (!$existingToday && !$yesterdaySnapshot) {
            // Buat snapshot kemarin sebagai baseline awal agar user bisa langsung membandingkan kemarin vs hari ini
            self::create([
                'snapshot_date' => $yesterday,
                'header_date'   => 'Baseline Awal',
                'categories'    => $serializedCategories,
                'checksum'      => $newChecksum,
                'has_changed'   => false,
            ]);

            return self::create([
                'snapshot_date' => $today,
                'header_date'   => $mentorPerformance->date ?? 'Terbaru',
                'categories'    => $serializedCategories,
                'checksum'      => $newChecksum,
                'has_changed'   => false,
            ]);
        }

        // Kasus 2: Snapshot hari ini sudah ada, dan terdeteksi perubahan checksum dari Google Spreadsheet
        if ($existingToday) {
            $prevCategories = $existingToday->categories ?? [];
            $existingChecksum = $existingToday->checksum;

            if ($existingChecksum !== $newChecksum) {
                // DETEKSI PERUBAHAN BARU DARI SPREADSHEET!
                $detectedChanges = self::detectCategoryDifferences($prevCategories, $serializedCategories);

                // Jika kemarin belum memiliki snapshot, selamatkan data LAMA hari ini sebagai snapshot kemarin
                if (!$yesterdaySnapshot && !empty($prevCategories)) {
                    // Bersihkan metadata _mutation_logs jika ada sebelum disimpan ke kemarin
                    $cleanPrevCategories = $prevCategories;
                    unset($cleanPrevCategories['_mutation_logs']);

                    self::create([
                        'snapshot_date' => $yesterday,
                        'header_date'   => $existingToday->header_date ?? 'Data Sebelum Pembaruan',
                        'categories'    => $cleanPrevCategories,
                        'checksum'      => $existingChecksum,
                        'has_changed'   => false,
                    ]);
                }

                // Simpan log mutasi ke dalam snapshot hari ini
                $existingLogs = $existingToday->categories['_mutation_logs'] ?? [];
                if (!empty($detectedChanges)) {
                    $newLogEntry = [
                        'timestamp' => now()->toIso8601String(),
                        'time'      => now()->format('H:i') . ' WIB',
                        'date'      => now()->translatedFormat('d M Y'),
                        'changes'   => $detectedChanges,
                    ];
                    $existingLogs[] = $newLogEntry;
                }

                $serializedCategories['_mutation_logs'] = $existingLogs;

                $existingToday->update([
                    'header_date' => $mentorPerformance->date ?? 'Terbaru',
                    'categories'  => $serializedCategories,
                    'checksum'    => $newChecksum,
                    'has_changed' => true,
                ]);

                return $existingToday;
            }

            // Jika belum ada snapshot kemarin tapi record hari ini ada
            if (!$yesterdaySnapshot) {
                $cleanPrevCategories = $serializedCategories;
                unset($cleanPrevCategories['_mutation_logs']);
                self::create([
                    'snapshot_date' => $yesterday,
                    'header_date'   => 'Baseline Rekam',
                    'categories'    => $cleanPrevCategories,
                    'checksum'      => $newChecksum,
                    'has_changed'   => false,
                ]);
            }

            return $existingToday;
        }

        // Kasus 3: Snapshot hari ini belum ada, tapi ada snapshot kemarin atau sebelumnya
        $previousSnapshot = self::where('snapshot_date', '<', $today)
            ->orderByDesc('snapshot_date')
            ->first();

        $hasChanged = false;
        $mutationLogs = [];
        if ($previousSnapshot) {
            if ($previousSnapshot->checksum !== $newChecksum) {
                $hasChanged = true;
                $detectedChanges = self::detectCategoryDifferences($previousSnapshot->categories ?? [], $serializedCategories);
                if (!empty($detectedChanges)) {
                    $mutationLogs[] = [
                        'timestamp' => now()->toIso8601String(),
                        'time'      => now()->format('H:i') . ' WIB',
                        'date'      => now()->translatedFormat('d M Y'),
                        'changes'   => $detectedChanges,
                    ];
                }
            }
        }

        if (!empty($mutationLogs)) {
            $serializedCategories['_mutation_logs'] = $mutationLogs;
        }

        return self::create([
            'snapshot_date' => $today,
            'header_date'   => $mentorPerformance->date ?? 'Terbaru',
            'categories'    => $serializedCategories,
            'checksum'      => $newChecksum,
            'has_changed'   => $hasChanged,
        ]);
    }

    /**
     * Membandingkan dua array kategori dan mendeteksi selisih target, realisasi, persentase per mentor.
     */
    public static function detectCategoryDifferences(array $prevCategories, array $currCategories): array
    {
        $changes = [];
        $validKeys = ['tk_pu_aktif', 'bpu_aktif', 'iuran'];

        foreach ($validKeys as $catKey) {
            $currCat = $currCategories[$catKey] ?? null;
            $prevCat = $prevCategories[$catKey] ?? null;
            if (!$currCat) continue;

            $catTitle = $currCat['title'] ?? $catKey;

            // Map mentor sebelumnya dengan normalisasi nama
            $prevMentorMap = [];
            if ($prevCat && !empty($prevCat['mentors'])) {
                foreach ($prevCat['mentors'] as $pm) {
                    $pName = self::normalizeName($pm['name'] ?? '');
                    if ($pName !== '') {
                        $prevMentorMap[$pName] = $pm;
                    }
                }
            }

            if (!empty($currCat['mentors'])) {
                foreach ($currCat['mentors'] as $cm) {
                    $cName = self::normalizeName($cm['name'] ?? '');
                    if ($cName === '') continue;

                    $pm = $prevMentorMap[$cName] ?? null;

                    // Nilai saat ini
                    $cTargetStr = (string) ($cm['target'] ?? '0');
                    $cRealStr   = (string) ($cm['realisasi'] ?? '0');
                    $cPercent   = (string) ($cm['percent'] ?? '0%');
                    $cGap       = (string) ($cm['gap'] ?? '0');

                    $cTargetInt = (int) preg_replace('/[^\d-]/', '', $cTargetStr);
                    $cRealInt   = (int) preg_replace('/[^\d-]/', '', $cRealStr);

                    if (!$pm) {
                        // Mentor baru ditambahkan
                        $changes[] = [
                            'name'         => $cm['name'] ?? '',
                            'category'     => $catTitle,
                            'category_key' => $catKey,
                            'is_new'       => true,
                            'diffTarget'   => $cTargetInt,
                            'diffReal'     => $cRealInt,
                            'prevTarget'   => '0',
                            'currTarget'   => $cTargetStr,
                            'prevReal'     => '0',
                            'currReal'     => $cRealStr,
                            'prevPercent'  => '0%',
                            'currPercent'  => $cPercent,
                            'prevGap'      => '0',
                            'currGap'      => $cGap,
                        ];
                        continue;
                    }

                    // Nilai sebelumnya
                    $pTargetStr = (string) ($pm['target'] ?? '0');
                    $pRealStr   = (string) ($pm['realisasi'] ?? '0');
                    $pPercent   = (string) ($pm['percent'] ?? '0%');
                    $pGap       = (string) ($pm['gap'] ?? '0');

                    $pTargetInt = (int) preg_replace('/[^\d-]/', '', $pTargetStr);
                    $pRealInt   = (int) preg_replace('/[^\d-]/', '', $pRealStr);

                    $diffTarget = $cTargetInt - $pTargetInt;
                    $diffReal   = $cRealInt - $pRealInt;

                    // Deteksi perubahan pada target, realisasi, gap, atau persentase
                    if ($diffTarget !== 0 || $diffReal !== 0 || $cPercent !== $pPercent || $cGap !== $pGap) {
                        $changes[] = [
                            'name'         => $cm['name'] ?? '',
                            'category'     => $catTitle,
                            'category_key' => $catKey,
                            'is_new'       => false,
                            'diffTarget'   => $diffTarget,
                            'diffReal'     => $diffReal,
                            'prevTarget'   => $pTargetStr,
                            'currTarget'   => $cTargetStr,
                            'prevReal'     => $pRealStr,
                            'currReal'     => $cRealStr,
                            'prevPercent'  => $pPercent,
                            'currPercent'  => $cPercent,
                            'prevGap'      => $pGap,
                            'currGap'      => $cGap,
                        ];
                    }
                }
            }
        }

        return $changes;
    }

    /**
     * Normalisasi nama mentor untuk pencocokan akurat.
     */
    public static function normalizeName(string $name): string
    {
        return strtolower(trim(preg_replace('/\s+/', ' ', $name)));
    }

    /**
     * Mengembalikan riwayat log mutasi perubahan yang terekam pada snapshot ini.
     */
    public function getMutationLogs(): array
    {
        $cats = is_array($this->categories) ? $this->categories : json_decode($this->categories, true);
        return $cats['_mutation_logs'] ?? [];
    }

    /**
     * Mengembalikan struktur categories yang diformat persis seperti objek spreadsheet,
     * menyaring metadata internal seperti _mutation_logs.
     */
    public function getFormattedCategories(): array
    {
        $cats = is_array($this->categories) ? $this->categories : json_decode($this->categories, true);
        $result = [];
        $validKeys = ['tk_pu_aktif', 'bpu_aktif', 'iuran'];

        foreach ($validKeys as $key) {
            if (!isset($cats[$key])) continue;
            $cat = $cats[$key];

            $mentorsList = collect($cat['mentors'] ?? [])->map(function ($m) {
                return (object) [
                    'no'        => $m['no'] ?? 0,
                    'name'      => $m['name'] ?? '',
                    'target'    => $m['target'] ?? '0',
                    'realisasi' => $m['realisasi'] ?? '0',
                    'gap'       => $m['gap'] ?? '0',
                    'percent'   => $m['percent'] ?? '0%',
                ];
            });

            $totalObj = null;
            if (!empty($cat['total'])) {
                $totalObj = (object) [
                    'target'    => $cat['total']['target'] ?? '0',
                    'realisasi' => $cat['total']['realisasi'] ?? '0',
                    'gap'       => $cat['total']['gap'] ?? '0',
                    'percent'   => $cat['total']['percent'] ?? '0%',
                ];
            }

            $result[$key] = [
                'title'   => $cat['title'] ?? '',
                'unit'    => $cat['unit'] ?? '',
                'mentors' => $mentorsList,
                'total'   => $totalObj,
            ];
        }

        return $result;
    }
}
