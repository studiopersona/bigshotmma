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
	    Route::post('refresh', 'AuthenticateController@refresh');
	    Route::post('register', 'AuthenticateController@register');
	    Route::resource('events', 'EventsController', ['only' => ['index','show']]);
	    Route::get('player/{id}/contests', 'ContestsController@index');
	    Route::get('player/contests-entered', 'ContestParticipantsController@getByPlayer');
	    Route::get('event/{id}/contests', 'ContestsController@eventLimited');
	    Route::get('contest/{id}/players', 'ContestsController@getParticipants');
	    Route::get('contest/{id}/fights', 'ContestsController@getFights');
	    Route::resource('contests', 'ContestsController');
	    Route::resource('picks', 'PicksController');
	    Route::get('contest/{id}/picks', 'PicksController@showCard');
	    Route::resource('powerups', 'PowerUpsController');
	    Route::get('contest-types', 'ContestTypesController@index');
	    Route::get('power-ups', 'PowerUpsController@index');
	    Route::get('finishes', 'FinishesController@index');
	    Route::get('contest/{id}/results', 'FightResultsController@index');
	    Route::get('contest/{id}/standings/{withplayers?}', 'PicksController@getStandings');
	    Route::get('player-name', 'UsersController@getPlayerName');
	});

});
