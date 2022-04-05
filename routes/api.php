<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\EventController;
use \App\Http\Controllers\EventUpdateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['as' => 'api'], function() {
    Route::resource('/events', EventController::class);

    Route::post('/update-event/{id}', [EventController::class, 'updateRecord']);

    Route::post('/bulk-delete', [App\Http\Controllers\EventController::class, 'bulkDelete']);

    Route::get('/export/{events}', [EventController::class, 'export']);
});
