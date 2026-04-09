<?php

namespace App\Http\Controllers;

use App\Services\ResultsApiClient;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResultsController extends Controller
{
    private const START_DATE = '2026-03-26';

    public function __construct(private readonly ResultsApiClient $resultsApiClient)
    {
    }

    public function index(Request $request): View|JsonResponse
    {
        $startDate = CarbonImmutable::createFromFormat('!Y-m-d', self::START_DATE, 'UTC');
        $today = CarbonImmutable::now('UTC')->startOfDay();

        $selectedDate = (string) $request->query('date', $today->toDateString());

        // Guard against invalid or out-of-range dates in the query string.
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
            $selectedDate = $today->toDateString();
        }

        $selected = CarbonImmutable::createFromFormat('!Y-m-d', $selectedDate, 'UTC');

        if ($selected === false || $selected->format('Y-m-d') !== $selectedDate) {
            $selected = $today;
            $selectedDate = $today->toDateString();
        }

        if ($selected->lt($startDate) || $selected->gt($today)) {
            $selected = $today;
            $selectedDate = $today->toDateString();
        }

        $availableDates = [];
        $cursor = $today;
        while ($cursor->gte($startDate)) {
            $availableDates[] = [
                'value' => $cursor->toDateString(),
                'label' => $cursor->format('d/m/Y'),
            ];
            $cursor = $cursor->subDay();
        }

        $records = [];
        $fetchError = null;

        try {
            $records = $this->resultsApiClient->getByDate($selectedDate);
        } catch (\Throwable $exception) {
            report($exception);
            $fetchError = 'We could not fetch results right now. Please try again in a moment.';
        }

        $payload = [
            'availableDates' => $availableDates,
            'selectedDate' => $selectedDate,
            'records' => $records,
            'summary' => $this->buildSummary($records),
            'fetchError' => $fetchError,
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'selectedDate' => $selectedDate,
                'html' => view('partials.results-grid', $payload)->render(),
            ]);
        }

        return view('results', $payload);
    }

    private function buildSummary(array $records): array
    {
        $wins = 0;
        $losses = 0;

        foreach ($records as $record) {
            if (! is_array($record)) {
                continue;
            }

            if ((bool) ($record['isWinner'] ?? false)) {
                $wins++;

                continue;
            }

            $losses++;
        }

        $total = $wins + $losses;
        $winRate = $total > 0 ? round(($wins / $total) * 100, 1) : 0.0;

        return [
            'total' => $total,
            'wins' => $wins,
            'losses' => $losses,
            'winRate' => $winRate,
        ];
    }
}
