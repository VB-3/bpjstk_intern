<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\MentorPerformanceSnapshot;
use App\Models\User;
use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;

class MentorHistoryController extends Controller
{
    public function index(Request $request, GoogleSheetsService $sheetsService)
    {
        // Proteksi: Hanya superadmin medankota yang memiliki hak akses
        if (auth()->user()?->email !== 'medankota@superadmin.com') {
            abort(403, 'Akses khusus untuk superadmin medankota.');
        }

        // Auto-record snapshot hari ini dari Google Spreadsheet jika ada data terbaru
        try {
            $currentPerformance = $sheetsService->getMentorPerformance();
            if (!empty($currentPerformance->categories)) {
                MentorPerformanceSnapshot::recordCurrentSnapshot($currentPerformance);
            }
        } catch (\Exception $e) {
            \Log::warning('Auto snapshot mentor performance gagal: ' . $e->getMessage());
        }

        // Tanggal yang dipilih oleh pengguna (default: hari ini)
        $selectedDate = $request->input('date', now()->toDateString());

        // Cari snapshot untuk tanggal yang dipilih
        $snapshot = MentorPerformanceSnapshot::where('snapshot_date', $selectedDate)->first();

        $isFallbackDate = false;
        // Jika tidak ada data persis pada tanggal tersebut, cari data terakhir per 24 jam sebelumnya
        if (!$snapshot) {
            $snapshot = MentorPerformanceSnapshot::where('snapshot_date', '<=', $selectedDate)
                ->orderByDesc('snapshot_date')
                ->first();

            if ($snapshot) {
                $isFallbackDate = true;
            } else {
                // Fallback terakhir: ambil snapshot paling awal atau terbaru yang ada
                $snapshot = MentorPerformanceSnapshot::orderByDesc('snapshot_date')->first();
            }
        }

        // Ambil snapshot pembanding sebelumnya (untuk cek riwayat perubahan)
        $previousSnapshot = null;
        if ($snapshot) {
            $previousSnapshot = MentorPerformanceSnapshot::where('snapshot_date', '<', $snapshot->snapshot_date)
                ->orderByDesc('snapshot_date')
                ->first();
        }

        // Daftar semua tanggal yang memiliki snapshot (untuk navigasi / kalender)
        $snapshotsList = MentorPerformanceSnapshot::orderByDesc('snapshot_date')->get();

        // Format data categories agar sesuai dengan format view
        $formattedCategories = $snapshot ? $snapshot->getFormattedCategories() : [];
        $mentorPerformance = (object) [
            'date'       => $snapshot ? ($snapshot->header_date ?? $snapshot->snapshot_date->locale('id')->isoFormat('D MMMM YYYY')) : 'Tidak Ada Data',
            'categories' => $formattedCategories,
        ];

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
            'totalMentors'
        ));
    }
}
