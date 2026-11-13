<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MentorPerformance;
use App\Models\User;
use App\Services\GoogleSheetsService;

class MentorPerformanceController extends Controller
{
    protected GoogleSheetsService $sheets;

    public function __construct(GoogleSheetsService $sheets)
    {
        $this->sheets = $sheets;
    }

    /**
     * Dashboard monitoring mentor - dengan data editable dari DB
     */
    public function index()
    {
        $mentorsList = User::where('role', 'mentor')
            ->withCount('mentees', 'assignedTasks')
            ->orderBy('name')
            ->get();

        $totalMentors = $mentorsList->count();

        // Ambil data performa: prioritas DB, fallback ke spreadsheet
        $dbPerformance = $this->getDbPerformance();

        // Jika DB kosong, coba ambil dari spreadsheet lalu simpan ke DB
        if ($this->isDbEmpty()) {
            $sheetData = $this->sheets->getMentorPerformance();
            if (!empty($sheetData->categories)) {
                $this->importFromSheet($sheetData);
                $dbPerformance = $this->getDbPerformance();
            }
        }

        // Jika DB masih kosong, gunakan data spreadsheet langsung (fallback)
        $useSheet = false;
        $mentorPerformance = null;
        if ($this->isDbEmpty()) {
            $mentorPerformance = $this->sheets->getMentorPerformance();
            $useSheet = true;
        }

        // Daftar nama mentor dari DB untuk dropdown autocomplete saat add row baru
        $mentorNames = MentorPerformance::select('mentor_name')
            ->distinct()
            ->orderBy('mentor_name')
            ->pluck('mentor_name');

        $period = MentorPerformance::max('period') ?? now()->format('Y-m');

        return view('superadmin.mentor_monitoring', compact(
            'totalMentors',
            'mentorsList',
            'dbPerformance',
            'mentorPerformance',
            'useSheet',
            'mentorNames',
            'period'
        ));
    }

    /**
     * Ambil semua data performa dari DB, diformat seperti struktur spreadsheet
     */
    protected function getDbPerformance(): array
    {
        $categories = [
            'tk_pu_aktif' => ['title' => 'TK PU AKTIF',  'unit' => 'Tenaga Kerja', 'mentors' => [], 'total' => null],
            'bpu_aktif'   => ['title' => 'TK BPU AKTIF', 'unit' => 'Tenaga Kerja', 'mentors' => [], 'total' => null],
            'iuran'       => ['title' => 'IURAN',         'unit' => 'Rupiah (Rp)',  'mentors' => [], 'total' => null],
        ];

        $rows = MentorPerformance::orderBy('category')->orderBy('no')->get();

        foreach ($rows as $row) {
            if (!isset($categories[$row->category])) continue;
            $categories[$row->category]['mentors'][] = $row;
        }

        // Hitung total per kategori
        foreach ($categories as $key => &$cat) {
            $mentors = $cat['mentors'];
            if (empty($mentors)) continue;

            $totalTarget    = 0;
            $totalRealisasi = 0;

            foreach ($mentors as $m) {
                $totalTarget    += (float) str_replace(['.', ',', '(', ')'], ['', '.', '-', ''], preg_replace('/[^0-9,.(]/', '', $m->target));
                $totalRealisasi += (float) str_replace(['.', ',', '(', ')'], ['', '.', '-', ''], preg_replace('/[^0-9,.(]/', '', $m->realisasi));
            }

            $gap     = $totalRealisasi - $totalTarget;
            $percent = $totalTarget > 0 ? round(($totalRealisasi / $totalTarget) * 100, 2) : 0;

            $cat['total'] = (object)[
                'target'    => number_format($totalTarget),
                'realisasi' => number_format($totalRealisasi),
                'gap'       => $gap < 0 ? '(' . number_format(abs($gap)) . ')' : number_format($gap),
                'percent'   => $percent . '%',
            ];
        }

        return $categories;
    }

    protected function isDbEmpty(): bool
    {
        return MentorPerformance::count() === 0;
    }

    /**
     * Import data dari Google Sheet ke DB (sekali jalan untuk inisialisasi)
     */
    protected function importFromSheet(object $sheetData): void
    {
        $period = now()->format('Y-m');

        foreach ($sheetData->categories as $catKey => $cat) {
            $no = 0;
            foreach ($cat['mentors'] as $mentor) {
                $no++;
                MentorPerformance::updateOrCreate(
                    [
                        'mentor_name' => $mentor->name,
                        'category'    => $catKey,
                        'period'      => $period,
                    ],
                    [
                        'no'        => $mentor->no ?? $no,
                        'target'    => $mentor->target,
                        'realisasi' => $mentor->realisasi,
                        'gap'       => $mentor->gap,
                        'percent'   => $mentor->percent,
                    ]
                );
            }
        }
    }

    /**
     * Store - tambah baris baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'mentor_name' => 'required|string|max:255',
            'category'    => 'required|in:tk_pu_aktif,bpu_aktif,iuran',
            'target'      => 'required|string|max:50',
            'realisasi'   => 'required|string|max:50',
        ]);

        $period = MentorPerformance::max('period') ?? now()->format('Y-m');

        // Hitung no urut berikutnya
        $no = MentorPerformance::where('category', $request->category)
            ->where('period', $period)
            ->max('no') + 1;

        $calculated = MentorPerformance::recalculate($request->target, $request->realisasi);

        MentorPerformance::create([
            'mentor_name' => strtoupper(trim($request->mentor_name)),
            'category'    => $request->category,
            'no'          => $no,
            'target'      => $request->target,
            'realisasi'   => $request->realisasi,
            'gap'         => $calculated['gap'],
            'percent'     => $calculated['percent'],
            'period'      => $period,
        ]);

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Data performa mentor berhasil ditambahkan.');
    }

    /**
     * Update - edit baris yang sudah ada
     */
    public function update(Request $request, MentorPerformance $performance)
    {
        $request->validate([
            'mentor_name' => 'required|string|max:255',
            'target'      => 'required|string|max:50',
            'realisasi'   => 'required|string|max:50',
        ]);

        $calculated = MentorPerformance::recalculate($request->target, $request->realisasi);

        $performance->update([
            'mentor_name' => strtoupper(trim($request->mentor_name)),
            'target'      => $request->target,
            'realisasi'   => $request->realisasi,
            'gap'         => $calculated['gap'],
            'percent'     => $calculated['percent'],
        ]);

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Data performa mentor berhasil diperbarui.');
    }

    /**
     * Destroy - hapus baris
     */
    public function destroy(MentorPerformance $performance)
    {
        $performance->delete();

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Sinkronisasi ulang dari Google Sheet (timpa data DB dengan data spreadsheet terbaru)
     */
    public function syncFromSheet()
    {
        try {
            $sheetData = $this->sheets->getMentorPerformance();

            if (empty($sheetData->categories)) {
                return redirect()->route('superadmin.dashboard')
                    ->with('error', 'Gagal mengambil data dari Google Spreadsheet.');
            }

            // Hapus data lama periode ini, lalu import ulang
            $period = now()->format('Y-m');
            MentorPerformance::where('period', $period)->delete();
            $this->importFromSheet($sheetData);

            // Clear cache spreadsheet agar data segar
            \Illuminate\Support\Facades\Cache::forget('google_sheets_mentor_performance');

            return redirect()->route('superadmin.dashboard')
                ->with('success', 'Data berhasil disinkronkan dari Google Spreadsheet.');
        } catch (\Exception $e) {
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
    }
}
