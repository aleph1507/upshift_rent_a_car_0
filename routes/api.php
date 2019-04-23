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


    Route::post('login', 'PassportController@login');
    Route::post('register', 'PassportController@register');


    Route::get('user', 'PassportController@profile')->middleware(['auth:api']);
    Route::post('logout', 'PassportController@logout')->middleware(['auth:api']);


    Route::get('/locations', 'LocationController@index')->middleware(['auth:api', 'scope:overview_locations']);
    Route::post('/locations', 'LocationController@store')->middleware(['auth:api', 'scope:create_locations']);
    Route::get('/locations/{location}', 'LocationController@show')->middleware(['auth:api', 'scope:view_locations']);
    Route::put('/locations/{location}', 'LocationController@update')->middleware(['auth:api', 'scope:update_locations']);
    Route::delete('/locations/{location}', 'LocationController@destroy')->middleware(['auth:api', 'scope:delete_locations']);

