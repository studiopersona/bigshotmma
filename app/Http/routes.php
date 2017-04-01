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
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\PasswordController@reset');
Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');

Route::group(['middleware' => ['web']], function () {

    Route::get('/', 'LanderController@index');

    Route::get('/play', function ()
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
	    Route::get('event/{id}/results', 'FightResultsController@getByEvent');
	    Route::get('contest/{id}/players', 'ContestsController@getParticipants');
	    Route::get('contest/{id}/fights', 'ContestsController@getFights');
	    Route::get('contest/{id}/players-records', 'PicksController@playersRecords');
	    Route::get('contest/{id}/players-overall-scores', 'ContestParticipantsController@playersScores');
	    Route::get('contest/{id}/quit', 'PicksController@quitContest');
	    Route::get('contest/{id}/picks', 'PicksController@showCard');
	    Route::get('contest/{id}/check-for-picks', 'PicksController@checkForPicks');
	    Route::get('contest/{id}/standings/{withplayers?}', 'PicksController@getStandings');
	    Route::get('contest/{id}/results', 'FightResultsController@getByContestId');
	    Route::get('contest/{id}/enter-player', 'ContestsController@enterPlayer');
	    Route::get('contest/{id}/has-player-entered', 'ContestsController@hasPlayerEntered');
	    Route::get('contest/{id}/winnings', 'ContestParticipantsController@determineWinnings');
	    Route::resource('contests', 'ContestsController');
	    Route::resource('picks', 'PicksController');
	    Route::resource('powerups', 'PowerUpsController');
	    Route::get('contest-types', 'ContestTypesController@index');
	    Route::get('power-ups', 'PowerUpsController@index');
	    Route::get('finishes', 'FinishesController@index');
	    Route::get('player-name', 'UsersController@getPlayerName');
	    Route::get('player-balance', 'UsersController@getPlayerBalance');
	    Route::get('player/{playerId}/entries', 'ContestsController@getPlayerEntries');
	    Route::get('player/get-picks', 'PicksController@getPicksforContestByPlayer');
	    Route::delete('player/pick/{id}', 'PicksController@destroy');
	    // Profile Routes ----------------------------------------------------
	    Route::get('profile', 'UsersController@profile');
	    Route::post('profile', 'UsersController@update');
	    Route::get('deposit-profile', 'UsersController@depositProfile');
	    Route::post('deposit-profile', 'UsersController@depositProfileUpdate');
	    Route::post('deposit', 'UsersController@makeDeposit');
	    Route::post('withdraw', 'UserBalancesController@makeWithdrawl');
	    //  Upload Routes ----------------------------------------------------
	    Route::post('upload-avatar-image', 'UsersController@uploadImage');
	    // Bogo Promo Routes -------------------------------------------------
	    Route::post('validate-promo-code', 'BogoPromoController@validateCode');
	    Route::get('player-promo', 'BogoPromoController@checkForActiveCode');
	    Route::post('update-promo', 'BogoPromoController@updateStage');
	    Route::post('backup-promo', 'BogoPromoController@backupStage');
	});

	Route::get('/terms-of-service', 'LanderController@terms');
	Route::get('/privacy-policy', 'LanderController@policy');
	Route::get('/contact-support', 'LanderController@contact');
	Route::post('/contact-support', 'LanderController@contactSubmit');

	Route::get('paypal-return', 'UsersController@completePaymentWithPayPal');
	Route::get('paypal-cancel', 'UsersController@paypalPaymentRejected');

});
