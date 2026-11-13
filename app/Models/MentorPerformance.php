<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentorPerformance extends Model
{
    protected $fillable = [
        'mentor_name',
        'category',
        'no',
        'target',
        'realisasi',
        'gap',
        'percent',
        'period',
    ];

    /**
     * Label kategori
     */
    public static function categoryLabel(string $category): string
    {
        return match($category) {
            'tk_pu_aktif' => 'TK PU AKTIF',
            'bpu_aktif'   => 'TK BPU AKTIF',
            'iuran'       => 'IURAN',
            default       => strtoupper($category),
        };
    }

    /**
     * Hitung GAP dan % capaian secara otomatis
     * Khusus kategori iuran: target & realisasi dalam Rupiah
     */
    public static function recalculate(string $target, string $realisasi): array
    {
        // Bersihkan format angka (hapus titik ribuan, koma desimal -> titik)
        $cleanTarget    = (float) str_replace(['.', ','], ['', '.'], preg_replace('/[^0-9,.]/', '', $target));
        $cleanRealisasi = (float) str_replace(['.', ','], ['', '.'], preg_replace('/[^0-9,.]/', '', $realisasi));

        $gap     = $cleanRealisasi - $cleanTarget;
        $percent = $cleanTarget > 0 ? round(($cleanRealisasi / $cleanTarget) * 100, 2) : 0;

        return [
            'gap'     => $gap < 0 ? '(' . number_format(abs($gap)) . ')' : number_format($gap),
            'percent' => $percent . '%',
        ];
    }
}
