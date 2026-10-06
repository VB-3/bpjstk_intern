<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Todo;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'intern')
            ->with('mentor')
            ->withSum('todos', 'bpu_achievement')
            ->orderBy('name')
            ->paginate(15);

        return view('superadmin.students.index', compact('students'));
    }

    public function edit(User $student)
    {
        if ($student->role !== 'intern') {
            abort(404);
        }
        
        $todos = $student->todos()->orderBy('date', 'desc')->get();
        return view('superadmin.students.edit', compact('student', 'todos'));
    }

    public function updateBpu(Request $request, Todo $todo)
    {
        $request->validate([
            'bpu_achievement' => 'required|integer|min:0'
        ]);

        $todo->update([
            'bpu_achievement' => $request->bpu_achievement
        ]);

        return redirect()->back()->with('success', 'BPU untuk tanggal ' . \Carbon\Carbon::parse($todo->date)->format('d/m/Y') . ' berhasil diperbarui.');
    }
}
