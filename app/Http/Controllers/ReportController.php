<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class ReportController extends Controller
{
    protected $eventModel;
    public function __construct()
    {
        $this->eventModel = new Event();
    }
    
    /**
     * Get the list of flights by it's start location.
     *
     * @param  Request $request
     * @return json
     */
    public function getFlightsByStartLocation(Request $request) {
        
        $error = $request->validate([
            'location' => 'required'
        ]);
        
        try {
            $location = isset($request->location) ? $request->location : '';

            $flights = $this->eventModel->getFlightsByStartLocation($location);

            if (count($flights)) {
                return response()->json([
                    'success' => true,
                    'totalCount' => count($flights),
                    'data' => $flights                
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
    
    /**
     * Get the list of flights running next week.
     *
     * @param  Request $request
     * @return json
     */
    public function nextWeekFlights(Request $request) {
        
        try {            
            $fromDate = $this->getNextWeekMonday();
            $toDate = date('Y-m-d', strtotime($fromDate. ' + 6 days'));

            $flights = $this->eventModel->getFlightsNextWeek($fromDate, $toDate);

            if (count($flights)) {
                return response()->json([
                    'success' => true,
                    'totalCount' => count($flights),
                    'data' => $flights                
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
    
    /**
     * Get the list of stand by events next week.
     *
     * @param  Request $request
     * @return json
     */
    public function nextWeekStandByEvents(Request $request) {
        
        try {            
            $fromDate = $this->getNextWeekMonday();
            $toDate = date('Y-m-d', strtotime($fromDate. ' + 6 days'));

            $standByEvents = $this->eventModel->getStandByEventsNextWeek($fromDate, $toDate);

            if (count($standByEvents)) {
                return response()->json([
                    'success' => true,
                    'totalCount' => count($standByEvents),
                    'data' => $standByEvents                
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

    /**
     * Get all events between a date range.
     *
     * @param  Request $request
     * @return json
     */
    public function getAllEvents(Request $request) {

        $error = $request->validate([
            'fromDate' => 'required|date|date_format:Y-m-d',
            'toDate' => 'required|date|date_format:Y-m-d|after_or_equal:fromDate',
        ]);

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;

        try {
            $standByEvents = $this->eventModel->getEventsByDateRange($fromDate, $toDate);

            if (count($standByEvents)) {
                return response()->json([
                    'success' => true,
                    'totalCount' => count($standByEvents),
                    'data' => $standByEvents                
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

    /**
     * Note: we are taking current date as per document to fetch the next week start date.
     *
     */
    private function getNextWeekMonday() {
        $currentDate = '2022-01-14';   // as per mentioned in the document
            
        $date = new \DateTime($currentDate);
        $date->modify('next monday');

        return $date->format('Y-m-d');
    }
}
