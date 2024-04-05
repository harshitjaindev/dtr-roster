<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProcessRoster;
use App\Models\Event;
use App\Models\Roster;
use Illuminate\Support\Facades\Auth;

class EventsContoller extends Controller
{    
    /**
     * Function is used to upload the roster in html format.
     *
     * @param  Request $request : html attachment in roster field , airline
     * @return json
     */
    public function uploadRoster (Request $request)
    {
        $error = $request->validate([
            'roster' => 'required|mimes:html,htm|max:4096',
            'airline' => 'required'
        ]);

        $file = $request->file('roster');
        $airline = strtoupper(trim($request->airline));

        // this will have list of airlines for which we are parsing roster layouts (may come from db)
        $supportedRosterLayout = ['DTR'];
        
        if (!in_array($airline, $supportedRosterLayout)) {
            return response()->json([
                'success' => false,
                'message' => 'Currently we are not supporting roster for airline : ' . $request->airline
            ], 404);
        }

        try {
            if ($rosterData = file_get_contents($file)) {
                
                // save uploaded file on storage
                $rosterId = $this->storeRosterFile($file, $airline);

                // parse and process uploaded file
                $processRosterService = new ProcessRoster();
                $response = $processRosterService->process($rosterData, $airline, $rosterId);

                return response()->json([
                    'success' => $response['success'],
                    'message' => $response['message']
                ], $response['statusCode']);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to read file content.'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Function is used to store uploaded file on local storage and save log in db.
     *
     * @param  $file : upload file
     * @param  $airline : for now it a string, but will change accordingly
     * @return json
     */
    private function storeRosterFile($file, $airline) {
        
        try {
            $uploadedFileName = $file->getClientOriginalName();

            $fileName = pathinfo($uploadedFileName, PATHINFO_FILENAME);
            $fileExtn = pathinfo($uploadedFileName, PATHINFO_EXTENSION);            
            $fileNameUnique = $fileName .= '_' . date('Y-m-d-H-i-s') . '.' . $fileExtn;
            
            // save file on local storage            
            $file->move(storage_path('app/rosters' . $airline), $fileNameUnique);

            // create log for uploaded roster in db
            $roster = new Roster; 
            $roster->fileName = $fileNameUnique;
            $roster->airline =  $airline;
            $roster->status = 'UPLOADED';
            $roster->uploadedBy = isset(Auth::user()->id) ? Auth::user()->id : 1; // 1 for test case only
            $roster->created_at = now(); 
            $roster->save();

            return $roster->id;

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Function is used to get the list of uploaded roster with there status (UPLOADED/PROCESSED).
     *
     * @param  $file : upload file
     * @param  $airline : for now it a string, but will change accordingly
     * @return json
     */
    public function getUploadedRosters(Request $request) {

        try {
            $uploadedRosters = Roster::orderBy('id', 'desc')->get()->toArray();

            if (count($uploadedRosters)) {
                return response()->json([
                    'success' => true,
                    'totalCount' => count($uploadedRosters),
                    'data' => $uploadedRosters                
                ], 200);
            } else {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No data found.'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }    
}
