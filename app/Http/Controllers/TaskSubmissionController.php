<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskSubmission;
use App\Models\AssignedTask;

class TaskSubmissionController extends Controller
{
    public function index()
    {
        // Otomatis nonaktifkan tugas yang sudah melewati batas tanggal tenggat
        AssignedTask::where('is_active', true)
            ->whereNotNull('deadline')
            ->where('deadline', '<', now())
            ->update(['is_active' => false]);

        $ongoingTask = AssignedTask::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('deadline')->orWhere('deadline', '>=', now());
            })
            ->first();

        // Cek apakah user sudah submit tugas aktif ini
        $alreadySubmitted = false;
        if ($ongoingTask) {
            $alreadySubmitted = TaskSubmission::where('user_id', auth()->id())
                ->where('assigned_task_id', $ongoingTask->id)
                ->exists();
        }

        $tasks = TaskSubmission::where('user_id', auth()->id())
            ->with('assignedTask')
            ->orderBy('submitted_at', 'desc')
            ->paginate(4);

        return view('tasks.index', compact('tasks', 'ongoingTask', 'alreadySubmitted'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'link' => 'required|url',
        ]);

        $ongoingTask = AssignedTask::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('deadline')->orWhere('deadline', '>=', now());
            })
            ->first();

        if (!$ongoingTask) {
            return redirect()->back()->with('error', 'Tidak ada tugas aktif saat ini atau tenggat waktu telah berakhir.');
        }

        // Cegah double submission untuk tugas yang sama
        $alreadySubmitted = TaskSubmission::where('user_id', auth()->id())
            ->where('assigned_task_id', $ongoingTask->id)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->back()->with('error', 'Anda sudah mengumpulkan tugas ini sebelumnya.');
        }

        TaskSubmission::create([
            'user_id'          => auth()->id(),
            'assigned_task_id' => $ongoingTask->id,
            'link'             => $request->link,
            'submitted_at'     => now(),
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil dikumpulkan!');
    }
}
