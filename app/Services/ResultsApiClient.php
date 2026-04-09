<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ResultsApiClient
{
    /**
     * Fetch result records for a single date from the external Node API.
     */
    public function getByDate(string $date): array
    {
        $baseUrl = rtrim((string) config('services.results_api.base_url'), '/');
        $timeout = (int) config('services.results_api.timeout', 10);

        if ($baseUrl === '') {
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

        // Support either a raw array or a wrapped payload with a data key.
        if (is_array($payload) && array_is_list($payload)) {
            return $this->normalizeList($payload);
        }

        $items = Arr::get($payload, 'data', []);

        return is_array($items) ? $this->normalizeList($items) : [];
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
