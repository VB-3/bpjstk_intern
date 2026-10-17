<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Intern Dashboard (Default)
    Route::get('/dashboard', function () {
        if (auth()->user()->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        }
        if (auth()->user()->isMentor()) {
            return redirect()->route('mentor.dashboard');
        }
        
        $attendances = \App\Models\Attendance::where('user_id', auth()->id())->orderBy('date', 'desc')->take(5)->get();
        $tasks = \App\Models\TaskSubmission::with('assignedTask')->where('user_id', auth()->id())->orderBy('submitted_at', 'desc')->take(5)->get();
        $totalBpu = \App\Models\Todo::where('user_id', auth()->id())->sum('bpu_achievement');

        // Jumlah tugas aktif yang belum dikumpulkan intern ini
        $submittedTaskIds = \App\Models\TaskSubmission::where('user_id', auth()->id())->pluck('assigned_task_id')->toArray();
        $pendingTasksCount = \App\Models\AssignedTask::where('is_active', true)
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->whereNull('deadline')->orWhere('deadline', '>=', now());
            })
            ->whereNotIn('id', $submittedTaskIds)
            ->count();

        // Leaderboard BPU
        $bpuLeaderboard = \App\Models\User::where('role', 'intern')
            ->withSum('todos', 'bpu_achievement')
            ->orderByDesc('todos_sum_bpu_achievement')
            ->get()
            ->map(function ($user) {
                $user->total_bpu = $user->todos_sum_bpu_achievement ?? 0;
                return $user;
            });
        $bpuTop3 = $bpuLeaderboard->take(3);
        
        return view('dashboard', compact('attendances', 'tasks', 'totalBpu', 'bpuLeaderboard', 'bpuTop3', 'pendingTasksCount'));
    })->name('dashboard');

    // Route leaderboard BPU (accessible oleh semua role)
    Route::get('/leaderboard/bpu', function () {
        $bpuLeaderboard = \App\Models\User::where('role', 'intern')
            ->withSum('todos', 'bpu_achievement')
            ->orderByDesc('todos_sum_bpu_achievement')
            ->get()
            ->map(function ($user) {
                $user->total_bpu = $user->todos_sum_bpu_achievement ?? 0;
                return $user;
            });
        return response()->json($bpuLeaderboard->map(fn($u) => ['name' => $u->name, 'total_bpu' => $u->total_bpu]));
    })->name('leaderboard.bpu');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Intern Routes
    Route::get('/attendances', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendances.index');
    Route::post('/attendances/checkin', [\App\Http\Controllers\AttendanceController::class, 'checkIn'])->name('attendances.checkin');
    Route::post('/attendances/checkout', [\App\Http\Controllers\AttendanceController::class, 'checkOut'])->name('attendances.checkout');

    Route::get('/todos', [\App\Http\Controllers\TodoController::class, 'index'])->name('todos.index');
    Route::post('/todos', [\App\Http\Controllers\TodoController::class, 'store'])->name('todos.store');
    Route::put('/todos/{todo}/bpu', [\App\Http\Controllers\TodoController::class, 'updateBpu'])->name('todos.updateBpu');
    Route::post('/todos/gdrive', [\App\Http\Controllers\TodoController::class, 'storeGdriveLink'])->name('todos.gdrive');

    Route::get('/tasks', [\App\Http\Controllers\TaskSubmissionController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [\App\Http\Controllers\TaskSubmissionController::class, 'store'])->name('tasks.store');

    // Mentor Routes
    Route::middleware([\App\Http\Middleware\EnsureIsMentor::class])->prefix('mentor')->name('mentor.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\MentorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/tasks', [\App\Http\Controllers\MentorTaskController::class, 'index'])->name('tasks.index');
        Route::post('/tasks', [\App\Http\Controllers\MentorTaskController::class, 'store'])->name('tasks.store');
        Route::get('/students', [\App\Http\Controllers\MentorStudentController::class, 'index'])->name('students.index');
    });



    // Super Admin Routes
    Route::middleware([\App\Http\Middleware\EnsureIsSuperAdmin::class])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('dashboard');

        // User Management
        Route::get('/users', [\App\Http\Controllers\SuperAdmin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [\App\Http\Controllers\SuperAdmin\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\SuperAdmin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [\App\Http\Controllers\SuperAdmin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [\App\Http\Controllers\SuperAdmin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\SuperAdmin\UserController::class, 'destroy'])->name('users.destroy');

        // Attendance Management
        Route::get('/attendances', [\App\Http\Controllers\SuperAdmin\AttendanceController::class, 'index'])->name('attendances.index');
        Route::get('/attendances/{attendance}/edit', [\App\Http\Controllers\SuperAdmin\AttendanceController::class, 'edit'])->name('attendances.edit');
        Route::put('/attendances/{attendance}', [\App\Http\Controllers\SuperAdmin\AttendanceController::class, 'update'])->name('attendances.update');
        Route::delete('/attendances/{attendance}', [\App\Http\Controllers\SuperAdmin\AttendanceController::class, 'destroy'])->name('attendances.destroy');

        // Todo Management
        Route::get('/todos', [\App\Http\Controllers\SuperAdmin\TodoController::class, 'index'])->name('todos.index');
        Route::get('/todos/{todo}/edit', [\App\Http\Controllers\SuperAdmin\TodoController::class, 'edit'])->name('todos.edit');
        Route::put('/todos/{todo}', [\App\Http\Controllers\SuperAdmin\TodoController::class, 'update'])->name('todos.update');
        Route::delete('/todos/{todo}', [\App\Http\Controllers\SuperAdmin\TodoController::class, 'destroy'])->name('todos.destroy');

        // Task Management
        Route::get('/tasks', [\App\Http\Controllers\SuperAdmin\TaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/create', [\App\Http\Controllers\SuperAdmin\TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [\App\Http\Controllers\SuperAdmin\TaskController::class, 'store'])->name('tasks.store');
        Route::get('/tasks/{task}/edit', [\App\Http\Controllers\SuperAdmin\TaskController::class, 'edit'])->name('tasks.edit');
        Route::put('/tasks/{task}', [\App\Http\Controllers\SuperAdmin\TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/tasks/{task}', [\App\Http\Controllers\SuperAdmin\TaskController::class, 'destroy'])->name('tasks.destroy');
        Route::patch('/tasks/{task}/approve', [\App\Http\Controllers\SuperAdmin\TaskController::class, 'approve'])->name('tasks.approve');
        Route::patch('/tasks/{task}/reject', [\App\Http\Controllers\SuperAdmin\TaskController::class, 'reject'])->name('tasks.reject');
        Route::delete('/submissions/{submission}', [\App\Http\Controllers\SuperAdmin\TaskController::class, 'destroySubmission'])->name('submissions.destroy');

        // Student (Anak Didik) Management
        Route::get('/students', [\App\Http\Controllers\SuperAdmin\StudentController::class, 'index'])->name('students.index');
        Route::get('/students/{student}/edit', [\App\Http\Controllers\SuperAdmin\StudentController::class, 'edit'])->name('students.edit');
        Route::patch('/students/todos/{todo}/bpu', [\App\Http\Controllers\SuperAdmin\StudentController::class, 'updateBpu'])->name('students.updateBpu');
    });
});

require __DIR__.'/auth.php';


