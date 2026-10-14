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

        $submissions = TaskSubmission::with('user', 'assignedTask')
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
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline'    => 'nullable|date',
            'is_active'   => 'nullable|boolean',
        ]);

        AssignedTask::create([
            'title'       => $request->title,
            'description' => $request->description,
            'deadline'    => $request->deadline,
            'is_active'   => $request->has('is_active') ? (bool)$request->is_active : true,
            'status'      => 'approved',   // SuperAdmin tidak perlu approval
            'created_by'  => auth()->id(),
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
