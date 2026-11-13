<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\TaskSubmission;
use App\Models\Todo;
use App\Models\AssignedTask;

class DashboardController extends Controller
{
    public function index()
    {
        // Jika akun yang login adalah superadmin khusus monitoring mentor (medankota@superadmin.com)
        if (auth()->user()->email === 'medankota@superadmin.com') {
            $sheetsService = app(\App\Services\GoogleSheetsService::class);
            $mentorPerformance = $sheetsService->getMentorPerformance();

            $totalMentors = User::where('role', 'mentor')->count();
            $mentorsList = User::where('role', 'mentor')
                ->withCount('mentees', 'assignedTasks')
                ->orderBy('name')
                ->get();

            $selectedDate = now()->toDateString();
            $snapshot = null;
            $previousSnapshot = null;
            $snapshotsList = collect();

            if (\Illuminate\Support\Facades\Schema::hasTable('mentor_performance_snapshots')) {
                if ($mentorPerformance && !empty($mentorPerformance->categories)) {
                    try {
                        \App\Models\MentorPerformanceSnapshot::recordCurrentSnapshot($mentorPerformance);
                    } catch (\Exception $e) {}
                }

                $snapshot = \App\Models\MentorPerformanceSnapshot::where('snapshot_date', $selectedDate)->first();
                if (!$snapshot) {
                    $snapshot = \App\Models\MentorPerformanceSnapshot::orderByDesc('snapshot_date')->first();
                }

                if ($snapshot) {
                    $previousSnapshot = \App\Models\MentorPerformanceSnapshot::where('snapshot_date', '<', $snapshot->snapshot_date)
                        ->orderByDesc('snapshot_date')
                        ->first();
                }

                $snapshotsList = \App\Models\MentorPerformanceSnapshot::orderByDesc('snapshot_date')->take(7)->get();
            }

            return view('superadmin.mentor_monitoring', compact(
                'totalMentors',
                'mentorPerformance',
                'mentorsList',
                'selectedDate',
                'snapshot',
                'previousSnapshot',
                'snapshotsList'
            ));
        }

        $totalUsers = User::count();
        $totalInterns = User::where('role', 'intern')->count();
        $totalMentors = User::where('role', 'mentor')->count();
        $totalAttendancesToday = Attendance::whereDate('date', today())->count();
        $totalTasksSubmitted = TaskSubmission::count();
        $totalBpu = Todo::sum('bpu_achievement');
        $totalAssignedTasks = AssignedTask::count();

        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();
        $recentSubmissions = TaskSubmission::with('user')->orderBy('submitted_at', 'desc')->take(5)->get();

        // Leaderboard BPU realtime dari Google Spreadsheet
        $sheetsService = app(\App\Services\GoogleSheetsService::class);
        $bpuLeaderboard = $sheetsService->getLeaderboard();
        $bpuTop3 = $bpuLeaderboard->take(3);
        $totalAccumulatedBpu = $bpuLeaderboard->isNotEmpty() 
            ? (int) $bpuLeaderboard->sum('total_bpu') 
            : (int) $totalBpu;
        $totalJkkJkm = (int) $bpuLeaderboard->sum('jkk_jkm');
        $totalJkkJkmJht = (int) $bpuLeaderboard->sum('jkk_jkm_jht');

        // Riwayat Tugas: Pisahkan Tugas Aktif dan Tugas Selesai (sudah melewati deadline)
        // 1. Tugas Aktif: is_active = true, tenggat belum terlewat (deadline null atau deadline >= now), dan sudah approved
        $activeTasks = AssignedTask::with('creator')
            ->withCount('submissions')
            ->where('is_active', true)
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->whereNull('deadline')->orWhere('deadline', '>=', now());
            })
            ->orderBy('deadline')
            ->take(6)
            ->get();

        // 2. Tugas Selesai: deadline sudah terlewat (approved/rejected) ATAU is_active = false dan sudah approved
        $completedTasks = AssignedTask::with('creator')
            ->withCount('submissions')
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->whereNotNull('deadline')->where('deadline', '<', now());
                })->orWhere('is_active', false);
            })
            ->orderBy('deadline', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('superadmin.dashboard', compact(
            'totalUsers',
            'totalInterns',
            'totalMentors',
            'totalAttendancesToday',
            'totalTasksSubmitted',
            'totalBpu',
            'totalAccumulatedBpu',
            'totalJkkJkm',
            'totalJkkJkmJht',
            'totalAssignedTasks',
            'recentUsers',
            'recentSubmissions',
            'bpuLeaderboard',
            'bpuTop3',
            'activeTasks',
            'completedTasks'
        ));
    }
}

