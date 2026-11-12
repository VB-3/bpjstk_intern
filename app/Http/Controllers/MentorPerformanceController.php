<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GoogleSheetsService;

class MentorPerformanceController extends Controller
{
    public function index(GoogleSheetsService $sheetsService)
    {
        $mentorUser = auth()->user();
        $userName = trim($mentorUser->name);

        // Ambil data performa realtime seluruh mentor dari Google Spreadsheet
        $allPerformance = $sheetsService->getMentorPerformance();

        // Cari data performa khusus mentor yang sedang login
        $myPerformance = [
            'tk_pu_aktif' => null,
            'bpu_aktif'   => null,
            'iuran'       => null,
        ];

        if (isset($allPerformance->categories)) {
            foreach ($allPerformance->categories as $catKey => $cat) {
                // Cocokkan nama mentor (case insensitive / partial matching)
                $matched = $cat['mentors']->first(function ($m) use ($userName) {
                    $mName = trim($m->name);
                    return strcasecmp($mName, $userName) === 0
                        || stripos($mName, $userName) !== false
                        || stripos($userName, $mName) !== false;
                });

                $myPerformance[$catKey] = [
                    'title'   => $cat['title'],
                    'unit'    => $cat['unit'],
                    'data'    => $matched,
                    'total'   => $cat['total'] ?? null,
                ];
            }
        }

        return view('mentor.performance.index', compact('allPerformance', 'myPerformance', 'mentorUser'));
    }
}
