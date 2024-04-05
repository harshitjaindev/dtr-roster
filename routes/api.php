<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventsContoller;
use App\Http\Controllers\ReportController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [AuthController::class, 'register'])->name('api.register');

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/upload-roster', [EventsContoller::class, 'uploadRoster'])->name('api.upload-roster');
    Route::get('/get-flights-by-start-location', [ReportController::class, 'getFlightsByStartLocation'])->name('api.flights-by-start-location');
    Route::get('/get-flights-next-week', [ReportController::class, 'nextWeekFlights'])->name('api.flights-next-week');
    Route::get('/get-standby-events-next-week', [ReportController::class, 'nextWeekStandByEvents'])->name('api.standby-events-next-week');
    Route::post('/get-all-events', [ReportController::class, 'getAllEvents'])->name('api.get-all-events');
    Route::get('/get-uploaded-rosters', [EventsContoller::class, 'getUploadedRosters'])->name('api.uploaded-roster');
});
