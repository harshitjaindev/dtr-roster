<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use WithoutMiddleware;

class UploadRosterTest extends TestCase
{
	public function testUploadRosterWithHTMLFileAndAirline()
    {
        $this->withoutMiddleware();
        
        // copying the test file, as it get removed once processed
		copy('tests/RosterData/Roster-CrewConnex-Main.html', 'tests/RosterData/Roster-CrewConnex.html');
		
		$file = new UploadedFile(
            "tests/RosterData/Roster-CrewConnex.html",
            "Roster-CrewConnex.html",
            "html", null, true
        );
        
        $response = $this->postJson(route('api.upload-roster'), [
            'roster' => $file,
            'airline' => 'DTR'
        ]);
        
        $response->assertSuccessful();
    }
}
