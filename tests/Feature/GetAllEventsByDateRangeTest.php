<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use WithoutMiddleware;

class GetAllEventsByDateRangeTest extends TestCase
{
    public function testGetEventsByDateRangeWithValidDateRange()
    {
        $this->withoutMiddleware();
		
		$response = $this->postJson(route('api.get-all-events'), [
            'fromDate' => '2022-01-13',
            'toDate'   => '2022-01-27',
        ]);
        $response->assertSuccessful();
    }
}
