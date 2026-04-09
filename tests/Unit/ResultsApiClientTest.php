<?php

namespace Tests\Unit;

use App\Services\ResultsApiClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ResultsApiClientTest extends TestCase
{
    public function test_it_does_not_call_the_api_when_the_date_is_missing(): void
    {
        config()->set('services.results_api.base_url', 'https://edgeware-server.onrender.com');

        Http::fake();

        $results = app(ResultsApiClient::class)->getByDate('');

        $this->assertSame([], $results);
        Http::assertNothingSent();
    }

    public function test_it_does_not_call_the_api_when_the_date_format_is_invalid(): void
    {
        config()->set('services.results_api.base_url', 'https://edgeware-server.onrender.com');

        Http::fake();

        $results = app(ResultsApiClient::class)->getByDate('09-04-2026');

        $this->assertSame([], $results);
        Http::assertNothingSent();
    }

    public function test_it_reads_the_wrapped_data_payload_from_the_results_api(): void
    {
        config()->set('services.results_api.base_url', 'https://edgeware-server.onrender.com');

        Http::fake([
            'https://edgeware-server.onrender.com/results*' => Http::response([
                'date' => '2026-04-09',
                'count' => 1,
                'data' => [
                    [
                        'fixtureId' => 123,
                        'home' => 'Alpha FC',
                        'away' => 'Beta United',
                        'currentHalf' => '2H',
                        'isWinner' => true,
                        'percentage' => 78,
                    ],
                ],
            ], 200),
        ]);

        $results = app(ResultsApiClient::class)->getByDate('2026-04-09');

        $this->assertSame([
            [
                'fixtureId' => 123,
                'home' => 'Alpha FC',
                'away' => 'Beta United',
                'currentHalf' => '2H',
                'isWinner' => true,
                'percentage' => 78,
            ],
        ], $results);

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://edgeware-server.onrender.com/results?date=2026-04-09';
        });
    }
}