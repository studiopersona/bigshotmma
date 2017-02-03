<?php

namespace Bsmma\Http\Controllers;

use Bsmma\Http\Requests;
use Bsmma\UserBalance;
use Illuminate\Http\Request;

class UserBalancesController extends Controller
{
	private $userBalance;

	public function __construct(UserBalance $userBalance)
	{
		$this->userBalance = $userBalance;
	}

    public function makeWithdrawl(Request $request)
    {
    	$user = \JWTAuth::parseToken()->authenticate();

    	$credits = $this->userBalance->where('user_id', $user->id)
                    ->whereIn('transaction_type_id', [2, 4, 5, 6])
                    ->sum('amount');

        $debits = $this->userBalance->where('user_id', $user->id)
                    ->whereIn('transaction_type_id', [1, 3])
                    ->sum('amount');

        $balance = $credits - $debits;

        if ( (int)$balance >= (int)$request->amount*100  ) {

	    	$this->userBalance->create([
	    		'user_id' => $user->id,
	    		'transaction_type_id' => 3,
	    		'amount' => (int)$request->amount*100,
	    		'is_credit' => 0,
	    		'live_funds' => 0,
	    	]);
	    } else {
	    	return reponse()->json(['failed' => true, 'msg' => 'You do not have sufficient funds to make this withdrawl.']);
	    }

    	event(new \Bsmma\Events\PlayerRequestedWithdrawl($user, $request->amount, $request->method));

    	return response()->json(['success' => true, 'msg' => 'Your request has been sent. Check your email for a confirmation.']);
    }
}
