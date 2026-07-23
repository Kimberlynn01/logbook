<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NagerDateService
{
    private const BASE_URL = 'https://date.nager.at/api/v3';
    private const COUNTRY_CODE = 'ID';

    /**
     * Cek apakah tanggal tertentu adalah hari libur nasional Indonesia.
     *
     * @return array{is_holiday: bool, holiday_name: ?string}
     */
    public function checkHoliday(\DateTimeInterface $date): array
    {
        $dateString = $date->format('Y-m-d');
        $holidays = $this->getHolidaysForYear((int) $date->format('Y'));

        $match = collect($holidays)->firstWhere('date', $dateString);

        return [
            'is_holiday' => (bool) $match,
            'holiday_name' => $match['localName'] ?? null,
        ];
    }

    /**
     * Ambil daftar hari libur satu tahun penuh dari Nager.Date, di-cache 1 hari
     * per tahun — supaya tiap mahasiswa submit logbook TIDAK memanggil API
     * publik berulang-ulang, cukup sekali per tahun per hari.
     */
    private function getHolidaysForYear(int $year): array
    {
        return Cache::remember(
            'nager-date:'.self::COUNTRY_CODE.":{$year}",
            now()->addDay(),
            function () use ($year) {
                try {
                    $response = Http::timeout(5)
                        ->get(self::BASE_URL."/PublicHolidays/{$year}/".self::COUNTRY_CODE);

                    if ($response->failed()) {
                        Log::warning('Nager.Date API merespons gagal', [
                            'status' => $response->status(),
                            'year' => $year,
                        ]);

                        return [];
                    }

                    return $response->json() ?? [];
                } catch (\Throwable $e) {
                    // API publik pihak ketiga down bukan alasan logbook gagal disimpan —
                    // fallback ke "bukan hari libur", proses tetap lanjut.
                    Log::warning('Nager.Date API tidak dapat dihubungi', [
                        'message' => $e->getMessage(),
                        'year' => $year,
                    ]);

                    return [];
                }
            }
        );
    }
}
