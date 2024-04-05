<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use WithoutMiddleware;

class ListingUploadedRosterTest extends TestCase
{
    public function testListingUploadedRoster()
    {
        $this->withoutMiddleware();
		
		$response = $this->getJson(route('api.uploaded-roster'));
        $response->assertSuccessful();
    }
}
