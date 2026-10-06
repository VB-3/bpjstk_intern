<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;
use App\Models\User;

class TodoController extends Controller
{
    public function index()
    {
        $query = Todo::with('user');

        if (request()->filled('date')) {
            $query->where('date', request('date'));
        }

        if (request()->filled('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        $todos = $query->orderBy('date', 'desc')->paginate(15)->appends(request()->only(['date', 'user_id']));
        $interns = User::where('role', 'intern')->orderBy('name')->get();
        
        return view('superadmin.todos.index', compact('todos', 'interns'));
    }

    public function edit(Todo $todo)
    {
        $todo->load('user');
        return view('superadmin.todos.edit', compact('todo'));
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'summary' => 'required|string',
            'bpu_achievement' => 'nullable|integer|min:0',
            'date' => 'required|date',
        ]);

        $todo->update([
            'summary' => $request->summary,
            'bpu_achievement' => $request->bpu_achievement ?? 0,
            'date' => $request->date,
        ]);

        return redirect()->route('superadmin.todos.index')->with('success', 'Data To-Do List berhasil diperbarui.');
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->route('superadmin.todos.index')->with('success', 'Data To-Do List berhasil dihapus.');
    }
}
