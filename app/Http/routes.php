<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function () {

    Route::get('/', function ()
    {
        return view('index');
    });

    Route::group(['prefix' => 'api/v1'], function()
	{
	    Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
	    Route::post('authenticate', 'AuthenticateController@authenticate');
	    Route::post('register', 'AuthenticateController@register');
	    Route::resource('events', 'EventsController', ['only' => ['index','show']]);
	    Route::get('player/{id}/contests', 'ContestsController@index');
	    Route::resource('contests', 'ContestsController');
	    Route::resource('picks', 'PicksController');
	    Route::resource('powerups', 'PowerUpsController');
	});

});
