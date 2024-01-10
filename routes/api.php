<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\FlightController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']);

Route::group([
    'prefix' => 'v1',
    'middleware'=> ['auth:sanctum'],
], function () {

    Route::resource('airports', AirportController::class)->except([
        'create', 'edit'
    ]);

    Route::resource('flights', FlightController::class)->except([
        'create', 'edit'
    ]);
});