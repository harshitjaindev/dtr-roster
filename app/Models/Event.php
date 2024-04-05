<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    use HasFactory;

    /**
     * get the list of flights that starts from a given location 
     */
    public function getFlightsByStartLocation($location)
    {
        return DB::table('events')
            ->select("*")
            ->where('fromstn', $location)
            ->get()->toArray();
    }

    /**
     * get the list of flights next week 
     */
    public function getFlightsNextWeek($fromDate, $toDate) {
        return DB::table('events')
            ->select("*")
            ->whereNotIn('activity', ['OFF', 'SBY', 'CAR'])
            ->where('eventdate', '>=', $fromDate)
            ->where('eventdate', '<=', $toDate)
            ->get()->toArray();
    }

    /**
     * get the list of stand by events next week
     */
    public function getStandByEventsNextWeek($fromDate, $toDate) {
        return DB::table('events')
            ->select("*")
            ->where('activity', '=', 'SBY')
            ->where('eventdate', '>=', $fromDate)
            ->where('eventdate', '<=', $toDate)
            ->get()->toArray();
    }

    /**
     * get the list of events between a given date range
     */
    public function getEventsByDateRange($fromDate, $toDate) {
        return DB::table('events')
            ->select("*")
            ->where('eventdate', '>=', $fromDate)
            ->where('eventdate', '<=', $toDate)
            ->get()->toArray();
    }
}
