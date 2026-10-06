<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignedTask;
use App\Models\TaskSubmission;

class MentorTaskController extends Controller
{
    public function index()
    {
        $tasks = AssignedTask::where('created_by', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $submissions = TaskSubmission::whereHas('user', function ($q) {
                $q->where('mentor_id', auth()->id());
            })
            ->with('user')
            ->orderBy('submitted_at', 'desc')
            ->paginate(10, ['*'], 'submissions_page');

        return view('mentor.tasks.index', compact('tasks', 'submissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline'    => 'nullable|date',
        ]);

        AssignedTask::create([
            'title'       => $request->title,
            'description' => $request->description,
            'deadline'    => $request->deadline,
            'is_active'   => false,        // tidak aktif sampai disetujui
            'status'      => 'pending',    // harus disetujui super admin
            'created_by'  => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil dikirim dan menunggu persetujuan Super Admin.');
    }

    // destroy dihapus — hanya Super Admin yang bisa menghapus
}
