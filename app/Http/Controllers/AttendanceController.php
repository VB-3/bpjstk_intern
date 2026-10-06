<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Todo;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::where('user_id', auth()->id())->orderBy('date', 'desc')->paginate(4);
        $todayAttendance = Attendance::where('user_id', auth()->id())->where('date', date('Y-m-d'))->first();
        return view('attendances.index', compact('attendances', 'todayAttendance'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'type' => 'required|in:harian,luar_harian',
            'check_in_photo' => 'required',
            'check_in_location' => 'required',
        ]);

        $image = $request->check_in_photo;
        $imageParts = explode(";base64,", $image);
        $imageTypeAux = explode("image/", $imageParts[0]);
        $imageType = $imageTypeAux[1];
        $imageBase64 = base64_decode($imageParts[1]);
        $fileName = 'checkin_' . auth()->id() . '_' . time() . '.' . $imageType;
        Storage::disk('public')->put('attendances/' . $fileName, $imageBase64);

        Attendance::create([
            'user_id' => auth()->id(),
            'date' => date('Y-m-d'),
            'type' => $request->type,
            'check_in_time' => now(),
            'check_in_photo' => 'attendances/' . $fileName,
            'check_in_location' => $request->check_in_location,
        ]);

        return redirect()->back()->with('success', 'Check-in berhasil!');
    }

    public function checkOut(Request $request)
    {
        $attendance = Attendance::where('user_id', auth()->id())->where('date', date('Y-m-d'))->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'Anda belum melakukan check-in hari ini.');
        }

        if ($attendance->check_out_time) {
            return redirect()->back()->with('error', 'Anda sudah melakukan check-out hari ini.');
        }

        // Validasi To-Do List
        $todo = Todo::where('user_id', auth()->id())->where('date', date('Y-m-d'))->first();
        if (!$todo) {
            return redirect()->back()->with('error', 'Anda harus mengisi To-Do List (Rangkuman Kegiatan) hari ini sebelum melakukan Check-out.');
        }

        $request->validate([
            'check_out_photo' => 'required',
            'check_out_location' => 'required',
        ]);

        $image = $request->check_out_photo;
        $imageParts = explode(";base64,", $image);
        $imageTypeAux = explode("image/", $imageParts[0]);
        $imageType = $imageTypeAux[1];
        $imageBase64 = base64_decode($imageParts[1]);
        $fileName = 'checkout_' . auth()->id() . '_' . time() . '.' . $imageType;
        Storage::disk('public')->put('attendances/' . $fileName, $imageBase64);

        $attendance->update([
            'check_out_time' => now(),
            'check_out_photo' => 'attendances/' . $fileName,
            'check_out_location' => $request->check_out_location,
        ]);

        return redirect()->back()->with('success', 'Check-out berhasil!');
    }
}
