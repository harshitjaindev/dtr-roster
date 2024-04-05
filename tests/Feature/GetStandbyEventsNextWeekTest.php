<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use WithoutMiddleware;

class GetStandbyEventsNextWeekTest extends TestCase
{

    public function testGetStandbyEventsNextWeek()
    {
        $this->withoutMiddleware();
		
		$response = $this->getJson(route('api.standby-events-next-week'));
        $response->assertSuccessful();
    }


}
