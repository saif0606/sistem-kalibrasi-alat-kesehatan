<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = '1DHhL_YELkImqnR3DgC0hYnlvqe9tB-Z-Tyebs_8o8CM';
        
        $this->client = new Client();
        $this->client->setApplicationName('Sistem Kalibrasi Alat Kesehatan');
        $this->client->setScopes([Sheets::SPREADSHEETS]);
        $this->client->setAuthConfig(storage_path('app/google-credentials.json'));
        $this->client->setAccessType('offline');

        $this->service = new Sheets($this->client);
    }

    /**
     * Tambahkan baris baru ke tab tertentu
     */
    public function appendRow($range, $values)
    {
        try {
            $body = new Sheets\ValueRange([
                'values' => [$values]
            ]);
            $params = [
                'valueInputOption' => 'USER_ENTERED'
            ];
            
            $result = $this->service->spreadsheets_values->append(
                $this->spreadsheetId,
                $range,
                $body,
                $params
            );
            return $result;
        } catch (\Exception $e) {
            Log::error('Google Sheets Append Error: ' . $e->getMessage());
            return null;
        }
    }
}
