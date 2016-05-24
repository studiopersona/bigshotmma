<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use Bsmma\Http\Requests;

use Bsmma\User;
use Bsmma\UserBalance;
use Bsmma\divStrong\Transformers\ProfileTransformer as ProfileTransformer;
use Bsmma\Http\Requests\ProfileRequest;
use Bsmma\Http\Requests\DepositProfileRequest;

class UsersController extends ApiController
{
    public function __construct(User $user, ProfileTransformer $profileTransformer, UserBalance $userBalance)
    {
    	$this->user = $user;
        $this->userBalance = $userBalance;
    	$this->profileTransformer = $profileTransformer;
    }

    public function getPlayerName()
    {
    	$user = \JWTAuth::parseToken()->authenticate();

    	return response()->json(['player_name'=>$user->player_name]);
    }

    public function getPlayerBalance()
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $credits = $this->userBalance->where('user_id', $user->id)
                    ->whereIn('transaction_type_id', [2, 4])
                    ->sum('amount');

        $debits = $this->userBalance->where('user_id', $user->id)
                    ->whereIn('transaction_type_id', [1, 3])
                    ->sum('amount');

        return response()->json(['playerBalance' => ($credits - $debits)/100 ]);
    }

    public function profile()
    {
    	$user = \JWTAuth::parseToken()->authenticate();

    	return $this->respond([
            'profile' => [
            	'name' => $user->player_name,
            	'email' => $user->email,
            	'avatar' => 'will be a hash',
            ],
        ]);
    }

    public function update(ProfileRequest $request)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $userInfo = $this->user->where('id', $user->id)
                        ->first();

        $userInfo->player_name = $request->player_name;

        if ( $request->new_password !== '' && Auth::attempt(['email' => $user->email, 'password' => $request->old_password]) ) {
            $userInfo->password = bcrypt($request->new_password);
        }

        return ($userInfo->save()) ? $this->respond([
            'success' => true,
            'msg' => 'Your profile was updated successfully',
        ]) : $this->respond([
            'success' => false,
            'msg' => 'There was a problem updating your profile'
        ]);
    }

    public function depositProfile()
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $userInfo = $this->user->with([
                            'stripeDetail',
                            'stripeDetail.creditCardType',
                            'paypalEmail'
                        ])
                        ->where('id', $user->id)
                        ->first();

        return $this->respond([
            'profile' => [
                'name' => $user->player_name,
                'email' => $user->email,
                'lastname' => $user->lastname,
                'firstname' => $user->firstname,
                'address' => $user->address1,
                'address2' => $user->address2,
                'city' => $user->city,
                'state' => $user->state,
                'zipcode' => $user->zipcode,
                'merchant' => $user->merchant_id,
                'stripeId' => (is_null($userInfo->stripeDetail)) ? 0 : $userInfo->stripeDetail->stripe_id,
                'ccDigits' => (is_null($userInfo->stripeDetail)) ? '' : $userInfo->stripeDetail->cc_digits,
                'ccImageName' => (is_null($userInfo->stripeDetail)) ? '' : $userInfo->stripeDetail->creditCardType->image_name,
                'paypalEmail' => (is_null($userInfo->paypalEmail)) ? '' : $userInfo->paypalEmail->email,
            ],
        ]);
    }

    public function depositProfileUpdate(DepositProfileRequest $request)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $userInfo = $this->user->where('id', $user->id)
                        ->first();

        $userInfo->lastname = $request->lastname;
        $userInfo->firstname = $request->firstname;
        $userInfo->address1 = $request->address;
        $userInfo->address2 = $request->address2;
        $userInfo->city = $request->city;
        $userInfo->state = $request->state;
        $userInfo->zipcode = $request->zipcode;
        $userInfo->merchant_id = (int)$request->merchant_id;

        return ($userInfo->save()) ? $this->respond([
            'success' => true,
            'msg' => 'Your profile was updated successfully',
        ]) : $this->respond([
            'success' => false,
            'msg' => 'There was a problem updating your profile'
        ]);
    }

    public function makeDeposit(Request $request)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $userInfo = $this->user->where('id', $user->id)
                        ->first();

        // Beta code version !!!!!!!!!!!!!!
        $userBalance = new $this->userBalance;
        $userBalance->user_id = $user->id;
        $userBalance->amount = $request->amount - 35;
        $userBalance->is_credit = 1;
        $userBalance->transaction_type_id = 4;
        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

        return ($userBalance->save()) ? $this->respond([
            'success' => true,
            'msg' => 'Your deposit was successfully processed',
        ]) : $this->respond([
            'success' => false,
            'msg' => 'There was a problem processing your deposit'
        ]);
    }

}
