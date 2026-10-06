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
        $totalUsers = User::count();
        $totalInterns = User::where('role', 'intern')->count();
        $totalMentors = User::where('role', 'mentor')->count();
        $totalAttendancesToday = Attendance::whereDate('date', today())->count();
        $totalTasksSubmitted = TaskSubmission::count();
        $totalBpu = Todo::sum('bpu_achievement');
        $totalAssignedTasks = AssignedTask::count();

        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();
        $recentSubmissions = TaskSubmission::with('user')->orderBy('submitted_at', 'desc')->take(5)->get();

        return view('superadmin.dashboard', compact(
            'totalUsers',
            'totalInterns',
            'totalMentors',
            'totalAttendancesToday',
            'totalTasksSubmitted',
            'totalBpu',
            'totalAssignedTasks',
            'recentUsers',
            'recentSubmissions'
        ));
    }
}

