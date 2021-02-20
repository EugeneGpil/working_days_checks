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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(
    ['namespace' => 'WorkingDays'],
    function ($router) {
        Route::get('is_it_working_day', 'WorkingDaysController@isItWorkingDay');
        Route::get('get_next_working_day', 'WorkingDaysController@getNextWorkingDay');
        Route::get('get_working_day_number', 'WorkingDaysController@getWorkingDayNumber');
    }
);
