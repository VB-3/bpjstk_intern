<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\MentorPerformanceSnapshot;
use App\Models\User;
use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class MentorHistoryController extends Controller
{
    public function index(Request $request, GoogleSheetsService $sheetsService)
    {
        // Proteksi: Hanya superadmin medankota yang memiliki hak akses
        if (auth()->user()?->email !== 'medankota@superadmin.com') {
            abort(403, 'Akses khusus untuk superadmin medankota.');
        }

        $selectedDate = $request->input('date', now()->toDateString());
        $dbMigrated = Schema::hasTable('mentor_performance_snapshots');
        $snapshot = null;
        $previousSnapshot = null;
        $isFallbackDate = false;
        $snapshotsList = collect();

        // Ambil data live dari spreadsheet sebagai fallback atau untuk auto-snapshot
        $currentPerformance = null;
        try {
            $currentPerformance = $sheetsService->getMentorPerformance();
        } catch (\Exception $e) {
            Log::warning('Gagal mengambil data live spreadsheet: ' . $e->getMessage());
        }

        if ($dbMigrated) {
            // Auto-record snapshot hari ini dari Google Spreadsheet
            if ($currentPerformance && !empty($currentPerformance->categories)) {
                try {
                    MentorPerformanceSnapshot::recordCurrentSnapshot($currentPerformance);
                } catch (\Exception $e) {
                    Log::warning('Auto snapshot mentor performance gagal: ' . $e->getMessage());
                }
            }

            try {
                // Cari snapshot untuk tanggal yang dipilih
                $snapshot = MentorPerformanceSnapshot::where('snapshot_date', $selectedDate)->first();

                // Jika tidak ada data persis pada tanggal tersebut, cari data terakhir per 24 jam sebelumnya
                if (!$snapshot) {
                    $snapshot = MentorPerformanceSnapshot::where('snapshot_date', '<=', $selectedDate)
                        ->orderByDesc('snapshot_date')
                        ->first();

                    if ($snapshot) {
                        $isFallbackDate = true;
                    } else {
                        // Fallback terakhir: ambil snapshot terbaru yang ada
                        $snapshot = MentorPerformanceSnapshot::orderByDesc('snapshot_date')->first();
                    }
                }

                // Ambil snapshot pembanding sebelumnya (untuk cek riwayat perubahan)
                if ($snapshot) {
                    $previousSnapshot = MentorPerformanceSnapshot::where('snapshot_date', '<', $snapshot->snapshot_date)
                        ->orderByDesc('snapshot_date')
                        ->first();
                }

                // Daftar semua tanggal yang memiliki snapshot (untuk navigasi / kalender)
                $snapshotsList = MentorPerformanceSnapshot::orderByDesc('snapshot_date')->get();
            } catch (\Exception $e) {
                Log::error('Error querying MentorPerformanceSnapshot: ' . $e->getMessage());
            }
        }

        // Format data categories agar siap ditampilkan di view
        if ($snapshot) {
            $formattedCategories = $snapshot->getFormattedCategories();
            $mentorPerformance = (object) [
                'date'       => $snapshot->header_date ?? $snapshot->snapshot_date->translatedFormat('d F Y'),
                'categories' => $formattedCategories,
            ];
        } elseif ($currentPerformance && !empty($currentPerformance->categories)) {
            // Fallback jika belum ada snapshot di database: gunakan data live dari spreadsheet
            $mentorPerformance = $currentPerformance;
        } else {
            $mentorPerformance = (object) [
                'date'       => 'Tidak Ada Data',
                'categories' => [],
            ];
        }

        // Daftar akun mentor untuk informasi
        $mentorsList = User::where('role', 'mentor')
            ->withCount('mentees', 'assignedTasks')
            ->orderBy('name')
            ->get();
        $totalMentors = $mentorsList->count();

        return view('superadmin.mentor_history', compact(
            'selectedDate',
            'snapshot',
            'previousSnapshot',
            'isFallbackDate',
            'snapshotsList',
            'mentorPerformance',
            'mentorsList',
            'totalMentors',
            'dbMigrated'
        ));
    }
}
