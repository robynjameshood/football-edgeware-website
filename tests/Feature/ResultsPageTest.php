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
}