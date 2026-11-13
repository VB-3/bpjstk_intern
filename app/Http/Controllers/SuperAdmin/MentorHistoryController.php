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

        $today = now()->toDateString();
        $selectedDate = $request->input('date', $today);
        $dbMigrated = Schema::hasTable('mentor_performance_snapshots');
        $snapshot = null;
        $previousSnapshot = null;
        $isFallbackDate = false;
        $snapshotsList = collect();
        $changes = [];
        $mutationLogs = [];

        // 1. Ambil data live dari spreadsheet
        $currentPerformance = null;
        try {
            $currentPerformance = $sheetsService->getMentorPerformance();
        } catch (\Exception $e) {
            Log::warning('Gagal mengambil data live spreadsheet: ' . $e->getMessage());
        }

        // 2. Jika database tabel snapshot sudah tersedia
        if ($dbMigrated) {
            // Auto-record / update snapshot hari ini dari Google Spreadsheet
            if ($currentPerformance && !empty($currentPerformance->categories)) {
                try {
                    MentorPerformanceSnapshot::recordCurrentSnapshot($currentPerformance);
                } catch (\Exception $e) {
                    Log::warning('Auto snapshot mentor performance gagal: ' . $e->getMessage());
                }
            }

            try {
                // Cari snapshot persis untuk tanggal yang dipilih user
                $snapshot = MentorPerformanceSnapshot::where('snapshot_date', $selectedDate)->first();

                // Ambil daftar seluruh snapshot yang ada untuk navigasi kalender
                $snapshotsList = MentorPerformanceSnapshot::orderByDesc('snapshot_date')->get();

                // Jika snapshot ada, cari snapshot pembanding tepat sebelum tanggal tersebut
                if ($snapshot) {
                    $previousSnapshot = MentorPerformanceSnapshot::where('snapshot_date', '<', $snapshot->snapshot_date)
                        ->orderByDesc('snapshot_date')
                        ->first();

                    // Ambil log mutasi internal yang tersimpan
                    $mutationLogs = $snapshot->getMutationLogs();

                    // Hitung perbedaan antara snapshot yang dipilih dengan snapshot sebelumnya
                    if ($previousSnapshot) {
                        $currCats = is_array($snapshot->categories) ? $snapshot->categories : json_decode($snapshot->categories, true);
                        $prevCats = is_array($previousSnapshot->categories) ? $previousSnapshot->categories : json_decode($previousSnapshot->categories, true);

                        $changes = MentorPerformanceSnapshot::detectCategoryDifferences($prevCats ?? [], $currCats ?? []);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error querying MentorPerformanceSnapshot: ' . $e->getMessage());
            }
        }

        // 3. Format data categories agar siap ditampilkan di view
        if ($snapshot) {
            $formattedCategories = $snapshot->getFormattedCategories();
            $mentorPerformance = (object) [
                'date'       => $snapshot->header_date ?? $snapshot->snapshot_date->translatedFormat('d F Y'),
                'categories' => $formattedCategories,
            ];
        } elseif ($selectedDate === $today && $currentPerformance && !empty($currentPerformance->categories)) {
            // Jika memilih hari ini dan belum ada snapshot DB: tampilkan data live
            $mentorPerformance = $currentPerformance;
        } else {
            // Tanggal di masa lalu yang memang belum memiliki snapshot
            $mentorPerformance = (object) [
                'date'       => 'Tidak Ada Rekaman Data',
                'categories' => [],
            ];
        }

        // 4. Daftar akun mentor untuk informasi ringkasan
        $mentorsList = User::where('role', 'mentor')
            ->withCount('mentees', 'assignedTasks')
            ->orderBy('name')
            ->get();
        $totalMentors = $mentorsList->count();

        return view('superadmin.mentor_history', compact(
            'selectedDate',
            'today',
            'snapshot',
            'previousSnapshot',
            'isFallbackDate',
            'snapshotsList',
            'mentorPerformance',
            'mentorsList',
            'totalMentors',
            'dbMigrated',
            'changes',
            'mutationLogs'
        ));
    }
}
