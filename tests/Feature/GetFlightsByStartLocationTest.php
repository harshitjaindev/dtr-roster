<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use WithoutMiddleware;

class GetFlightsByStartLocationTest extends TestCase
{

    public function testGetFlightsByStartLocation()
    {
        $this->withoutMiddleware();
		
		$response = $this->getJson(route('api.flights-by-start-location', [
            'location' => 'KRP',
        ]));
        $response->assertSuccessful();
    }

    /*
	public function testGetFlightsFromLocationWithInvalidLocation()
    {
        $response = $this->postJson(route('api.flights-by-start-location'), [
            'location' => 'LAXX',
        ]);
        $response->assertJsonValidationErrors(['location']);
    }
	*/
}
