<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;

use Bsmma\User;

class UsersController extends Controller
{
    public function __construct(User $user)
    {
    	$this->user = $user;
    }

    public function getPlayerName()
    {
    	$user = \JWTAuth::parseToken()->authenticate();

    	return response()->json(['player_name'=>$user->player_name]);
    }
}
