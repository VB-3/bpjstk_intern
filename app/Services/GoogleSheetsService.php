<?php

namespace App\Services;

use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GoogleSheetsService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = env('GOOGLE_SPREADSHEET_ID', '1Jo2qkM90WfM6DoIdLeDAcVEANO89TW3Nkr9lvbK8uBw');
        
        $this->client = new Google_Client();
        $this->client->setApplicationName('Portal Magang BPJSTK');
        $this->client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $this->client->setAccessType('offline');
        
        // Path to credentials.json (The user needs to provide this)
        $credentialsPath = storage_path('app/google-credentials.json');
        
        if (file_exists($credentialsPath)) {
            $this->client->setAuthConfig($credentialsPath);
            $this->service = new Google_Service_Sheets($this->client);
        }
    }

    /**
     * Mengambil data leaderboard BPU realtime dari sheet 'leaderboard'
     * Di-cache selama 30 detik untuk efisiensi dan performa tinggi
     */
    public function getLeaderboard()
    {
        return Cache::remember('google_sheets_leaderboard', 30, function () {
            return $this->fetchLeaderboardFromSheet();
        });
    }

    protected function fetchLeaderboardFromSheet()
    {
        try {
            $url = "https://docs.google.com/spreadsheets/d/{$this->spreadsheetId}/gviz/tq?tqx=out:csv&sheet=leaderboard";
            $response = Http::timeout(6)->get($url);

            if (!$response->successful()) {
                Log::warning('Failed fetching leaderboard from Google Sheet: HTTP ' . $response->status());
                return $this->fallbackLeaderboardFromDatabase();
            }

            $csv = $response->body();
            $lines = explode("\n", trim($csv));
            if (count($lines) < 2) {
                return $this->fallbackLeaderboardFromDatabase();
            }

            // Buang baris header pertama
            array_shift($lines);

            $results = collect();
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                $row = str_getcsv($line);
                $nim = trim($row[0] ?? '');
                $name = trim($row[1] ?? '');
                $grandTotal = trim($row[4] ?? '');

                // Abaikan jika baris total atau kosong
                if (empty($nim) && empty($name)) continue;
                if (stripos($name, 'total') !== false || stripos($nim, 'total') !== false) continue;
                if (!is_numeric($grandTotal)) continue;

                $results->push((object)[
                    'nim'       => $nim,
                    'name'      => $name ?: ($nim ?: 'Peserta Magang'),
                    'total_bpu' => (int)$grandTotal,
                ]);
            }

            if ($results->isEmpty()) {
                return $this->fallbackLeaderboardFromDatabase();
            }

            return $results->sortByDesc('total_bpu')->values();

        } catch (Exception $e) {
            Log::error('Exception in fetchLeaderboardFromSheet: ' . $e->getMessage());
            return $this->fallbackLeaderboardFromDatabase();
        }
    }

    protected function fallbackLeaderboardFromDatabase()
    {
        return \App\Models\User::where('role', 'intern')
            ->withSum('todos', 'bpu_achievement')
            ->orderByDesc('todos_sum_bpu_achievement')
            ->get()
            ->map(function ($user) {
                return (object)[
                    'nim'       => $user->nim,
                    'name'      => $user->name,
                    'total_bpu' => $user->todos_sum_bpu_achievement ?? 0,
                ];
            });
    }

    public function appendTodoRow($name, $gdriveLink, $summary, $date)
    {
        if (!$this->service) {
            Log::warning('Google Sheets service is not configured. Missing credentials.json.');
            return false;
        }

        try {
            // Kita asumsikan Sheet bernama "Laporan Harian" atau sheet pertama (default "Sheet1").
            // Sesuaikan nama sheet jika berbeda, misalnya 'Laporan Harian!A:D'
            $range = 'Sheet1'; 

            // Value yang dimasukkan. Kolom 2 adalah link google drive, Kolom 1 misalnya nama, dll.
            // Sesuai permintaan: link gdrive di kolom 2.
            $values = [
                [$name, $gdriveLink, $summary, $date]
            ];

            $body = new Google_Service_Sheets_ValueRange([
                'values' => $values
            ]);

            $params = [
                'valueInputOption' => 'RAW'
            ];

            $result = $this->service->spreadsheets_values->append($this->spreadsheetId, $range, $body, $params);
            return $result;
        } catch (Exception $e) {
            Log::error('Gagal mengirim ke Google Sheets: ' . $e->getMessage());
            return false;
        }
    }
}
