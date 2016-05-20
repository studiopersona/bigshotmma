<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;

use Bsmma\User;
use Bsmma\divStrong\Transformers\ProfileTransformer as ProfileTransformer;
use Bsmma\Http\Requests\ProfileRequest;

class UsersController extends ApiController
{
    public function __construct(User $user, ProfileTransformer $profileTransformer)
    {
    	$this->user = $user;
    	$this->profileTransformer = $profileTransformer;
    }

    public function getPlayerName()
    {
    	$user = \JWTAuth::parseToken()->authenticate();

    	return response()->json(['player_name'=>$user->player_name]);
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
        $userInfo->email = $request->email;

        return ($userInfo->save()) ? $this->respond([
            'success' => true,
            'msg' => 'Your profile was updated successfully',
        ]) : $this->respond([
            'success' => false,
            'msg' => 'There was a problem updating your profile'
        ]);
    }

}
