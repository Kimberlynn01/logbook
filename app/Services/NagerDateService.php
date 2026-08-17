<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NagerDateService
{
    private const BASE_URL = 'https://date.nager.at/api/v3';
    private const COUNTRY_CODE = 'ID';

    public function checkHoliday(\DateTimeInterface $date): array
    {
        if ($weekendName = $this->weekendName($date)) {
            return [
                'is_holiday' => true,
                'holiday_name' => $weekendName,
            ];
        }

        $dateString = $date->format('Y-m-d');
        $holidays = $this->getHolidaysForYear((int) $date->format('Y'));

        $match = collect($holidays)->firstWhere('date', $dateString);

        return [
            'is_holiday' => (bool) $match,
            'holiday_name' => $match['localName'] ?? null,
        ];
    }

    private function weekendName(\DateTimeInterface $date): ?string
    {
        return match ((int) $date->format('N')) {
            6 => 'Sabtu',
            7 => 'Minggu',
            default => null,
        };
    }

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
