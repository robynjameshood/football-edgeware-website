<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ResultsApiClient
{
    private const DATE_FORMAT_PATTERN = '/^\d{4}-\d{2}-\d{2}$/';

    /**
     * Fetch result records for a single date from the external Node API.
     */
    public function getByDate(string $date): array
    {
        $baseUrl = rtrim((string) config('services.results_api.base_url'), '/');
        $timeout = (int) config('services.results_api.timeout', 10);

        if ($baseUrl === '' || ! $this->isValidDate($date)) {
            return [];
        }

        $response = Http::timeout($timeout)
            ->acceptJson()
            ->get($baseUrl . '/results', [
                'date' => $date,
            ]);

        if (! $response->successful()) {
            return [];
        }

        $payload = $response->json();

        if (! is_array($payload)) {
            return [];
        }

        $items = Arr::get($payload, 'data', []);

        return is_array($items) && array_is_list($items) ? $this->normalizeList($items) : [];
    }

    private function isValidDate(string $date): bool
    {
        if (! preg_match(self::DATE_FORMAT_PATTERN, $date)) {
            return false;
        }

        $parsed = \DateTimeImmutable::createFromFormat('!Y-m-d', $date);

        return $parsed !== false && $parsed->format('Y-m-d') === $date;
    }

    /**
     * Keep only fields used by the view and apply safe defaults.
     */
    private function normalizeList(array $items): array
    {
        $results = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $results[] = [
                'fixtureId' => Arr::get($item, 'fixtureId'),
                'home' => (string) Arr::get($item, 'home', ''),
                'away' => (string) Arr::get($item, 'away', ''),
                'currentHalf' => (string) Arr::get($item, 'currentHalf', '-'),
                'isWinner' => (bool) Arr::get($item, 'isWinner', false),
                'percentage' => Arr::get($item, 'percentage'),
            ];
        }

        return $results;
    }
}
