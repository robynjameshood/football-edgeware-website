<?php

namespace Tests\Feature;

use App\Services\ResultsApiClient;
use Mockery;
use Tests\TestCase;

class ResultsPageTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_results_page_loads_successfully(): void
    {
        $mock = Mockery::mock(ResultsApiClient::class);
        $mock->shouldReceive('getByDate')
            ->once()
            ->andReturn([]);

        $this->app->instance(ResultsApiClient::class, $mock);

        $response = $this->get('/results');

        $response->assertOk();
        $response->assertSee('Daily Results');
    }

    public function test_results_page_ajax_refresh_returns_json(): void
    {
        $mock = Mockery::mock(ResultsApiClient::class);
        $mock->shouldReceive('getByDate')
            ->once()
            ->andReturn([]);

        $this->app->instance(ResultsApiClient::class, $mock);

        $response = $this->getJson('/results');

        $response->assertOk();
        $response->assertJsonStructure(['selectedDate', 'html']);
    }

    public function test_results_page_shows_half_specific_goal_labels(): void
    {
        $mock = Mockery::mock(ResultsApiClient::class);
        $mock->shouldReceive('getByDate')
            ->once()
            ->andReturn([
                [
                    'fixtureId' => 1,
                    'home' => 'Alpha FC',
                    'away' => 'Beta United',
                    'currentHalf' => '1H',
                    'isWinner' => true,
                    'percentage' => 65,
                ],
                [
                    'fixtureId' => 2,
                    'home' => 'Gamma FC',
                    'away' => 'Delta Town',
                    'currentHalf' => '2H',
                    'isWinner' => false,
                    'percentage' => 42,
                ],
            ]);

        $this->app->instance(ResultsApiClient::class, $mock);

        $response = $this->get('/results');

        $response->assertOk();
        $response->assertSee('First Half Goal');
        $response->assertSee('Second Half Goal');
        $response->assertSee('First Half List');
        $response->assertSee('Second Half List');
    }

    public function test_results_page_shows_first_and_second_half_final_day_percentages(): void
    {
        $mock = Mockery::mock(ResultsApiClient::class);
        $mock->shouldReceive('getByDate')
            ->once()
            ->andReturn([
                [
                    'fixtureId' => 1,
                    'home' => 'Alpha FC',
                    'away' => 'Beta United',
                    'currentHalf' => '1H',
                    'isWinner' => true,
                    'percentage' => 65,
                ],
                [
                    'fixtureId' => 2,
                    'home' => 'Gamma FC',
                    'away' => 'Delta Town',
                    'currentHalf' => '1H',
                    'isWinner' => false,
                    'percentage' => 42,
                ],
                [
                    'fixtureId' => 3,
                    'home' => 'Sigma FC',
                    'away' => 'Omega City',
                    'currentHalf' => '2H',
                    'isWinner' => true,
                    'percentage' => 71,
                ],
            ]);

        $this->app->instance(ResultsApiClient::class, $mock);

        $response = $this->get('/results');

        $response->assertOk();
        $response->assertSee('Final day percentage', false);
        $response->assertSee('First Half', false);
        $response->assertSee('Second Half', false);
        $response->assertSee('50.0%', false);
        $response->assertSee('100.0%', false);
        $response->assertSee('Fixtures', false);
        $response->assertSee('3', false);
    }
}