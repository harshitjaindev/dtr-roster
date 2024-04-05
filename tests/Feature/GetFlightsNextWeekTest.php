<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use WithoutMiddleware;

class GetFlightsNextWeekTest extends TestCase
{

    public function testGetFlightsNextWeek()
    {
        $this->withoutMiddleware();
		
		$response = $this->getJson(route('api.flights-next-week'));
        $response->assertSuccessful();
    }
}
