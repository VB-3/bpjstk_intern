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
        $attendances = Attendance::where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->orderBy('check_in_time', 'desc')
            ->paginate(6);

        // Ambil semua absensi hari ini (bisa lebih dari 1)
        $todayAttendances = Attendance::where('user_id', auth()->id())
            ->where('date', date('Y-m-d'))
            ->orderBy('check_in_time')
            ->get();

        // Cek status per tipe
        $todayHarian     = $todayAttendances->firstWhere('type', 'harian');
        $todayLuarHarian = $todayAttendances->firstWhere('type', 'luar_harian');

        return view('attendances.index', compact(
            'attendances',
            'todayAttendances',
            'todayHarian',
            'todayLuarHarian'
        ));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'type'           => 'required|in:harian,luar_harian',
            'check_in_photo' => 'required',
        ]);

        $type = $request->type;

        // Cek apakah tipe ini sudah check-in hari ini
        $existing = Attendance::where('user_id', auth()->id())
            ->where('date', date('Y-m-d'))
            ->where('type', $type)
            ->first();

        if ($existing) {
            $label = $type === 'harian' ? 'Harian' : 'Luar Harian';
            return redirect()->back()->with('error', "Kamu sudah check-in untuk kegiatan {$label} hari ini.");
        }

        $image       = $request->check_in_photo;
        $imageParts  = explode(";base64,", $image);
        $imageType   = explode("image/", $imageParts[0])[1];
        $imageBase64 = base64_decode($imageParts[1]);
        $fileName    = 'checkin_' . auth()->id() . '_' . $type . '_' . time() . '.' . $imageType;
        Storage::disk('public')->put('attendances/' . $fileName, $imageBase64);

        Attendance::create([
            'user_id'        => auth()->id(),
            'date'           => date('Y-m-d'),
            'type'           => $type,
            'check_in_time'  => now(),
            'check_in_photo' => 'attendances/' . $fileName,
        ]);

        $label = $type === 'harian' ? 'Harian' : 'Luar Harian';
        return redirect()->back()->with('success', "Check-in {$label} berhasil!");
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'type'            => 'required|in:harian,luar_harian',
            'check_out_photo' => 'required',
        ]);

        $type = $request->type;

        $attendance = Attendance::where('user_id', auth()->id())
            ->where('date', date('Y-m-d'))
            ->where('type', $type)
            ->first();

        $label = $type === 'harian' ? 'Harian' : 'Luar Harian';

        if (!$attendance) {
            return redirect()->back()->with('error', "Kamu belum check-in untuk kegiatan {$label} hari ini.");
        }

        if ($attendance->check_out_time) {
            return redirect()->back()->with('error', "Kamu sudah check-out untuk kegiatan {$label} hari ini.");
        }

        // Validasi To-Do List hanya untuk absensi Harian
        if ($type === 'harian') {
            $todo = Todo::where('user_id', auth()->id())->where('date', date('Y-m-d'))->first();
            if (!$todo) {
                return redirect()->back()->with('error', 'Kamu harus mengisi To-Do List (Rangkuman Kegiatan) hari ini sebelum Check-out Harian.');
            }
        }

        $image       = $request->check_out_photo;
        $imageParts  = explode(";base64,", $image);
        $imageType   = explode("image/", $imageParts[0])[1];
        $imageBase64 = base64_decode($imageParts[1]);
        $fileName    = 'checkout_' . auth()->id() . '_' . $type . '_' . time() . '.' . $imageType;
        Storage::disk('public')->put('attendances/' . $fileName, $imageBase64);

        $attendance->update([
            'check_out_time'  => now(),
            'check_out_photo' => 'attendances/' . $fileName,
        ]);

        return redirect()->back()->with('success', "Check-out {$label} berhasil!");
    }
}
