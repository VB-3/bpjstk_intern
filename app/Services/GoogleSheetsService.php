<?php

namespace App\Services;

use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Exception;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = '1KZSbCixC2odyLTgQhC18ZAjfi4mAR3Q8RqB-yBiUDU8';
        
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
