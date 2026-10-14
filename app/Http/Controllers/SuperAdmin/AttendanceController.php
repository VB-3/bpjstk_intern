<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;

class AttendanceController extends Controller
{
    public function index()
    {
        $query = Attendance::with('user');

        if (request()->filled('date')) {
            $query->where('date', request('date'));
        }

        if (request()->filled('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(15)->appends(request()->only(['date', 'user_id']));
        $interns = User::where('role', 'intern')->orderBy('name')->get();
        
        return view('superadmin.attendances.index', compact('attendances', 'interns'));
    }

    public function edit(Attendance $attendance)
    {
        $attendance->load('user');
        return view('superadmin.attendances.edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'date'           => 'required|date',
            'type'           => 'required|in:harian,luar_harian',
            'check_in_time'  => 'nullable|date',
            'check_out_time' => 'nullable|date',
        ]);

        $attendance->update([
            'date'           => $request->date,
            'type'           => $request->type,
            'check_in_time'  => $request->check_in_time,
            'check_out_time' => $request->check_out_time,
        ]);

        return redirect()->route('superadmin.attendances.index')->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('superadmin.attendances.index')->with('success', 'Data absensi berhasil dihapus.');
    }
}
