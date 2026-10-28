<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Todo;
use App\Services\GoogleSheetsService;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'intern')
            ->with('mentor')
            ->withSum('todos', 'bpu_achievement');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        // Ambil data leaderboard BPU real-time dari Google Spreadsheet
        $sheetsService = app(GoogleSheetsService::class);
        $bpuLeaderboard = $sheetsService->getLeaderboard();

        // Buat lookup map berdasarkan NIM dan Nama untuk pencocokan cepat O(1)
        $bpuByNim = [];
        $bpuByName = [];

        foreach ($bpuLeaderboard as $item) {
            $nim = trim((string)($item->nim ?? ''));
            if ($nim !== '') {
                $bpuByNim[$nim] = (int) $item->total_bpu;
            }
            $name = strtolower(preg_replace('/\s+/', ' ', trim((string)($item->name ?? ''))));
            if ($name !== '') {
                $bpuByName[$name] = (int) $item->total_bpu;
            }
        }

        // Pasangkan nilai BPU real time ke tiap peserta
        $students->getCollection()->transform(function ($student) use ($bpuByNim, $bpuByName) {
            $cleanNim = trim((string)($student->nim ?? ''));
            $cleanName = strtolower(preg_replace('/\s+/', ' ', trim((string)($student->name ?? ''))));

            if ($cleanNim !== '' && isset($bpuByNim[$cleanNim])) {
                $student->realtime_bpu = $bpuByNim[$cleanNim];
            } elseif ($cleanName !== '' && isset($bpuByName[$cleanName])) {
                $student->realtime_bpu = $bpuByName[$cleanName];
            } else {
                // Cek pencocokan parsial nama jika ada perbedaan format
                $matched = false;
                if ($cleanName !== '') {
                    foreach ($bpuByName as $sheetName => $score) {
                        if (str_contains($sheetName, $cleanName) || str_contains($cleanName, $sheetName)) {
                            $student->realtime_bpu = $score;
                            $matched = true;
                            break;
                        }
                    }
                }
                if (!$matched) {
                    $student->realtime_bpu = (int) ($student->todos_sum_bpu_achievement ?? 0);
                }
            }
            return $student;
        });

        return view('superadmin.students.index', compact('students'));
    }

    public function edit(User $student)
    {
        if ($student->role !== 'intern') {
            abort(404);
        }
        
        $todos = $student->todos()->orderBy('date', 'desc')->get();

        // BPU real time dari Google Spreadsheet untuk detail peserta
        $sheetsService = app(GoogleSheetsService::class);
        $bpuLeaderboard = $sheetsService->getLeaderboard();

        $cleanNim = trim((string)($student->nim ?? ''));
        $cleanName = strtolower(preg_replace('/\s+/', ' ', trim((string)($student->name ?? ''))));

        $realtimeBpu = null;
        if ($cleanNim !== '') {
            $item = $bpuLeaderboard->first(fn($i) => trim((string)($i->nim ?? '')) === $cleanNim);
            if ($item) $realtimeBpu = (int)$item->total_bpu;
        }
        if ($realtimeBpu === null && $cleanName !== '') {
            $item = $bpuLeaderboard->first(fn($i) => strtolower(preg_replace('/\s+/', ' ', trim((string)($i->name ?? '')))) === $cleanName);
            if ($item) $realtimeBpu = (int)$item->total_bpu;
        }
        if ($realtimeBpu === null) {
            $realtimeBpu = (int)$todos->sum('bpu_achievement');
        }

        return view('superadmin.students.edit', compact('student', 'todos', 'realtimeBpu'));
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
