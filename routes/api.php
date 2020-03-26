<?php

use Illuminate\Http\Request;

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
 
Route::prefix('implementation/{id}')->group(function () {
    Route::resource('task', 'GanttTaskController');
    Route::resource('link', 'GanttLinkController');
});

Route::prefix('pmo/{id_pmo}')->group(function () {
    Route::resource('task', 'GanttTaskPMOController');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
