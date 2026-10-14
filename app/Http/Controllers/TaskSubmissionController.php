<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskSubmission;
use App\Models\AssignedTask;

class TaskSubmissionController extends Controller
{
    public function index()
    {
        // Ambil semua tugas yang aktif dan belum melewati deadline
        $ongoingTasks = AssignedTask::where('is_active', true)
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->whereNull('deadline')->orWhere('deadline', '>=', now());
            })
            ->orderBy('deadline')
            ->get();

        // Tandai tugas mana saja yang sudah disubmit user ini
        $submittedTaskIds = TaskSubmission::where('user_id', auth()->id())
            ->pluck('assigned_task_id')
            ->toArray();

        $tasks = TaskSubmission::where('user_id', auth()->id())
            ->with('assignedTask')
            ->orderBy('submitted_at', 'desc')
            ->paginate(4);

        return view('tasks.index', compact('tasks', 'ongoingTasks', 'submittedTaskIds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'assigned_task_id' => 'required|exists:assigned_tasks,id',
            'link' => 'required|url',
        ]);

        $task = AssignedTask::where('id', $request->assigned_task_id)
            ->where('is_active', true)
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->whereNull('deadline')->orWhere('deadline', '>=', now());
            })
            ->first();

        if (!$task) {
            return redirect()->back()->with('error', 'Tugas tidak ditemukan, sudah tidak aktif, atau tenggat waktu telah berakhir.');
        }

        // Cegah double submission untuk tugas yang sama
        $alreadySubmitted = TaskSubmission::where('user_id', auth()->id())
            ->where('assigned_task_id', $task->id)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->back()->with('error', 'Anda sudah mengumpulkan tugas ini sebelumnya.');
        }

        TaskSubmission::create([
            'user_id'          => auth()->id(),
            'assigned_task_id' => $task->id,
            'link'             => $request->link,
            'submitted_at'     => now(),
        ]);

        return redirect()->back()->with('success', 'Tugas "' . $task->title . '" berhasil dikumpulkan!');
    }
}
