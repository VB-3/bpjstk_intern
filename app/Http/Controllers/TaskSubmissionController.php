<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskSubmission;

use App\Models\AssignedTask;

class TaskSubmissionController extends Controller
{
    public function index()
    {
        $ongoingTask = AssignedTask::where('is_active', true)->first();
        
        $tasks = TaskSubmission::where('user_id', auth()->id())->orderBy('submitted_at', 'desc')->paginate(4);
        return view('tasks.index', compact('tasks', 'ongoingTask'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'link' => 'required|url',
        ]);

        TaskSubmission::create([
            'user_id'      => auth()->id(),
            'link'         => $request->link,
            'submitted_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Link tugas berhasil disubmit.');
    }
}
