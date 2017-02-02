<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;

class UserBalancesController extends Controller
{
    public function makeWithdrawl(Request $requst)
    {
    	$user = \JWTAuth::parseToken()->authenticate();


    }
}
