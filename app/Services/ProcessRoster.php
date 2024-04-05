<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Roster;

class ProcessRoster
{
    /**
    * function is use to parse and process the roster content.
    *
    */  
    public function process($rosterData, $airline, $rosterId) {
        try {
            switch ($airline) {
                case "DTR":                    
                    $activityData = $this->parseDataDTR($rosterData);
                    break;
                case "Airline2":
                    // parsing roster Airline2
                    break;
                case "Airline3":
                    // parsing roster Airline3
                    break;
            }

            if (is_array($activityData) && count($activityData) > 0) {
                return $this->saveRosterData($activityData, $rosterId);
            } else {
                return [
                    'success' => false,
                    'message' => 'Alert!!! No event found on given roster. Please check the layout.',
                    'statusCode' => 404
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'statusCode' => 200
            ];            
        }
    }
    
    /**
    * function is used to identify the activity data based on html layout classes and parsing it.
    *
    */
    public function parseDataDTR($rosterData) {
        try {
        
            $dom = new \DOMDocument();
            @$dom->loadHTML($rosterData);
            
            $finder = new \DomXPath($dom);
            
            // list of classes having activity data
            $activityColumnClassess = [
                        'activitytablerow-date',
                        'activitytablerow-revision',
                        'activitytablerow-dc',
                        'activitytablerow-checkinutc',
                        'activitytablerow-checkoututc',
                        'activitytablerow-activity',
                        'activitytablerow-activityRemark',
                        'activitytablerow-fromstn',
                        'activitytablerow-stdutc',
                        'activitytablerow-tostn',
                        'activitytablerow-stautc',
                        'activitytablerow-AC/Hotel',
                        'activitytablerow-blockhours',
                        'activitytablerow-flighttime',
                        'activitytablerow-nighttime',
                        'activitytablerow-duration',
                        'activitytablerow-counter1',
                        'activitytablerow-Paxbooked',
                        'activitytablerow-Tailnumber'
                    ];


            $activityData = array();
            for ($classCount = 0; $classCount < count($activityColumnClassess); $classCount++) {

                $classname = $activityColumnClassess[$classCount];
                $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

                $rowNumber = -1;
                foreach ($nodes as $node) {         
                    if ($rowNumber == -1) {
                        $rowNumber++; continue;
                    }
                    $activityData[$rowNumber][$classname] =  preg_replace('/^\p{Z}+|\p{Z}+$/u', '', trim($node->textContent));
                    $rowNumber++ ;
                }

                $currentDate = '';
                for ($rowCount = 1; $rowCount < count($activityData); $rowCount++) {
                    $activityData[$rowCount]['activitytablerow-date'] = !empty(trim($activityData[$rowCount]['activitytablerow-date'])) ? $activityData[$rowCount]['activitytablerow-date'] : $activityData[$rowCount-1]['activitytablerow-date'];
                }
            }

            return $activityData;

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Unable to parse the given roster. Error : ' . $e->getMessage(),
                'statusCode' => 500
            ];
        }
    }

    /**
    * function is used to save the parsed html data into db.
    *
    */
    public function saveRosterData($activityData, $rosterId) {
        try {
            
            $insertDataArray = [];
            foreach ($activityData as $key => $activity) {
                $insertDataArray[$key]['eventdate'] = $this->formatEventDate($activity['activitytablerow-date']);
                $insertDataArray[$key]['revision'] = $activity['activitytablerow-revision'];
                $insertDataArray[$key]['dc'] = $activity['activitytablerow-dc'];
                $insertDataArray[$key]['checkinutc'] = $activity['activitytablerow-checkinutc'];
                $insertDataArray[$key]['checkoututc'] = $activity['activitytablerow-checkoututc'];
                $insertDataArray[$key]['activity'] = $activity['activitytablerow-activity'];
                $insertDataArray[$key]['activityRemark'] = $activity['activitytablerow-activityRemark'];
                $insertDataArray[$key]['fromstn'] = $activity['activitytablerow-fromstn'];
                $insertDataArray[$key]['stdutc'] = $activity['activitytablerow-stdutc'];
                $insertDataArray[$key]['tostn'] = $activity['activitytablerow-tostn'];
                $insertDataArray[$key]['stautc'] = $activity['activitytablerow-stautc'];
                $insertDataArray[$key]['acHotel'] = $activity['activitytablerow-AC/Hotel'];
                $insertDataArray[$key]['blockhours'] = $activity['activitytablerow-blockhours'];
                $insertDataArray[$key]['flighttime'] = $activity['activitytablerow-flighttime'];
                $insertDataArray[$key]['nighttime'] = $activity['activitytablerow-nighttime'];
                $insertDataArray[$key]['duration'] = $activity['activitytablerow-duration'];
                $insertDataArray[$key]['ext'] = $activity['activitytablerow-counter1'];
                $insertDataArray[$key]['paxbooked'] = $activity['activitytablerow-Paxbooked'];
                $insertDataArray[$key]['acreg'] = $activity['activitytablerow-Tailnumber'];
                $insertDataArray[$key]['user_id'] = isset(Auth::user()->id) ? Auth::user()->id : 1; // 1 for test case
                $insertDataArray[$key]['roster_id'] = $rosterId;
            }

            // save events into database
            Event::insert($insertDataArray);
            
            // update uploaded roster status as processed            
            Roster::where('id', $rosterId)
                ->update([
                    'status' => 'PROCESSED',
                    'totalEvents' => count($activityData)
                ]);
                
            return [
                'success' => true,
                'message' => 'Total number of events inserted : ' . count($activityData),
                'statusCode' => 200
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'statusCode' => 500
            ];
        }        
    }

    public function formatEventDate($eventDate) {
        $currentDate = '2022-01-14';  // this is as per the document date
        $year = date("Y", strtotime($currentDate));
        $month = date("m", strtotime($currentDate));
        $day = explode(' ' , $eventDate)[1];

        return $year . '-' . $month . '-' . $day;
    }
}