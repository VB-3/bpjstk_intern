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
     * Merekam / memperbarui snapshot harian (per 24 jam) dari data Google Spreadsheet
     */
    public static function recordCurrentSnapshot($mentorPerformance): ?self
    {
        if (empty($mentorPerformance->categories)) {
            return null;
        }

        $today = now()->toDateString();

        $serializedCategories = [];
        foreach ($mentorPerformance->categories as $key => $cat) {
            $mentors = [];
            foreach ($cat['mentors'] as $m) {
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

        $checksum = md5(json_encode($serializedCategories));

        // Cek snapshot hari sebelumnya untuk melihat apakah ada perubahan
        $previousSnapshot = self::where('snapshot_date', '<', $today)
            ->orderByDesc('snapshot_date')
            ->first();

        $hasChanged = true;
        if ($previousSnapshot && $previousSnapshot->checksum === $checksum) {
            $hasChanged = false;
        }

        return self::updateOrCreate(
            ['snapshot_date' => $today],
            [
                'header_date' => $mentorPerformance->date ?? 'Terbaru',
                'categories'  => $serializedCategories,
                'checksum'    => $checksum,
                'has_changed' => $hasChanged,
            ]
        );
    }

    /**
     * Mengembalikan struktur categories yang diformat persis seperti objek spreadsheet
     */
    public function getFormattedCategories(): array
    {
        $cats = is_array($this->categories) ? $this->categories : json_decode($this->categories, true);
        $result = [];

        foreach ($cats as $key => $cat) {
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
