<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class MentorStudentController extends Controller
{
    public function index()
    {
        // For now, getting all interns. Later this will be mapped to specific 'anak didik'
        $students = User::where('role', 'intern')->where('mentor_id', auth()->id())->paginate(15);
        return view('mentor.students.index', compact('students'));
    }
}

