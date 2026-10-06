<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\TaskSubmission;

class MentorDashboardController extends Controller
{
    public function index()
    {
        // For now, getting all interns. Later it can be filtered by mentor's mentees (anak didik)
        $interns = User::where('role', 'intern')
            ->where('mentor_id', auth()->id())
            ->withCount('attendances', 'taskSubmissions')
            ->withSum('todos', 'bpu_achievement')
            ->get();
        $totalInterns = $interns->count();
        $internIds = $interns->pluck('id');
        
        $totalAttendancesToday = Attendance::whereIn('user_id', $internIds)->whereDate('date', date('Y-m-d'))->count();
        $totalTasksSubmitted = TaskSubmission::whereIn('user_id', $internIds)->count();
        $recentSubmissions = TaskSubmission::whereIn('user_id', $internIds)->with('user')->orderBy('submitted_at', 'desc')->take(10)->get();
        $totalBpu = \App\Models\Todo::whereIn('user_id', $internIds)->sum('bpu_achievement');
        
        return view('mentor.dashboard', compact('interns', 'totalInterns', 'totalAttendancesToday', 'totalTasksSubmitted', 'recentSubmissions', 'totalBpu'));
    }
}

