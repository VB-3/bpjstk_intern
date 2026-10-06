<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignedTask;
use App\Models\TaskSubmission;

class TaskController extends Controller
{
    public function index()
    {
        $pendingTasks = AssignedTask::where('status', 'pending')
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'pending_page');

        $tasks = AssignedTask::whereIn('status', ['approved', 'rejected'])
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $submissions = TaskSubmission::with('user')
            ->orderBy('submitted_at', 'desc')
            ->paginate(10, ['*'], 'submissions_page');

        return view('superadmin.tasks.index', compact('tasks', 'submissions', 'pendingTasks'));
    }

    public function create()
    {
        return view('superadmin.tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->is_active) {
            // Nonaktifkan tugas lain yang sedang aktif
            AssignedTask::where('is_active', true)->update(['is_active' => false]);
        }

        AssignedTask::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'is_active' => $request->is_active ?? false,
        ]);

        return redirect()->route('superadmin.tasks.index')->with('success', 'Tugas berhasil dibuat.');
    }

    public function edit(AssignedTask $task)
    {
        return view('superadmin.tasks.edit', compact('task'));
    }

    public function update(Request $request, AssignedTask $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->is_active && !$task->is_active) {
            // Nonaktifkan tugas lain jika tugas ini diaktifkan
            AssignedTask::where('is_active', true)->where('id', '!=', $task->id)->update(['is_active' => false]);
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'is_active' => $request->is_active ?? false,
        ]);

        return redirect()->route('superadmin.tasks.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(AssignedTask $task)
    {
        $task->delete();
        return redirect()->route('superadmin.tasks.index')->with('success', 'Tugas berhasil dihapus.');
    }

    public function approve(AssignedTask $task)
    {
        // Nonaktifkan tugas lain yang sedang aktif
        AssignedTask::where('is_active', true)->where('id', '!=', $task->id)->update(['is_active' => false]);

        $task->update([
            'status'    => 'approved',
            'is_active' => true,
        ]);

        return redirect()->route('superadmin.tasks.index')->with('success', "Tugas \"{$task->title}\" telah disetujui dan diaktifkan.");
    }

    public function reject(AssignedTask $task)
    {
        $task->update([
            'status'    => 'rejected',
            'is_active' => false,
        ]);

        return redirect()->route('superadmin.tasks.index')->with('success', "Tugas \"{$task->title}\" telah ditolak.");
    }

    public function destroySubmission(TaskSubmission $submission)
    {
        $submission->delete();
        return redirect()->route('superadmin.tasks.index')->with('success', 'Pengumpulan tugas berhasil dihapus.');
    }
}
