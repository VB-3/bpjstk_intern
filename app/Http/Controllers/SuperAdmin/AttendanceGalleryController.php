<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceGalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()?->canViewAttendancePhoto()) {
                abort(403, 'Anda tidak memiliki akses ke galeri foto absensi.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        // Ambil semua intern yang punya foto absensi
        $interns = User::where('role', 'intern')
            ->whereHas('attendances', function ($q) {
                $q->where(function ($q2) {
                    $q2->whereNotNull('check_in_photo')
                       ->orWhereNotNull('check_out_photo');
                });
            })
            ->orderBy('name')
            ->get();

        // Filter bulan & tahun (default: bulan ini)
        $filterMonth = $request->input('month', now()->format('Y-m'));

        // Ambil semua absensi per intern yang punya foto, difilter bulan
        $galleryData = [];

        foreach ($interns as $intern) {
            $query = Attendance::where('user_id', $intern->id)
                ->where(function ($q) {
                    $q->whereNotNull('check_in_photo')
                      ->orWhereNotNull('check_out_photo');
                })
                ->orderBy('date', 'desc')
                ->orderBy('check_in_time', 'asc');

            if ($filterMonth) {
                $query->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$filterMonth]);
            }

            $attendances = $query->get();

            if ($attendances->isNotEmpty()) {
                $galleryData[] = [
                    'intern'      => $intern,
                    'attendances' => $attendances,
                    'total_foto'  => $attendances->filter(fn($a) => $a->check_in_photo || $a->check_out_photo)->count(),
                ];
            }
        }

        // Daftar bulan yang tersedia untuk dropdown filter
        $availableMonths = Attendance::where(function ($q) {
                $q->whereNotNull('check_in_photo')
                  ->orWhereNotNull('check_out_photo');
            })
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month")
            ->distinct()
            ->orderByDesc('month')
            ->pluck('month');

        return view('superadmin.attendance_gallery', compact(
            'galleryData',
            'filterMonth',
            'availableMonths',
            'interns'
        ));
    }
}
