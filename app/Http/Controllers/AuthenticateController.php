<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;
use Bsmma\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Bsmma\User;

class AuthenticateController extends ApiController
{
    public function __construct()
    {
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        $this->middleware('jwt.auth', ['except' => ['authenticate','register']]);
    }

    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        dd($credentials);

        try {
            // attempt to verify the credentials and create a token for the current user
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->respondInvaildCredentials('Email Password Mismatch');
            }
        } catch (JWTException $e) {
            // error while attempting to encode token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good, return the token
        return response()->json(compact('token'));
    }

    public function register(Request $request)
    {
        $credentials = $request->only('email', 'password', 'playerName');
        dd($credentials);

        try {
            User:create($credentials);
        } catch (Illuminate\Database\QueryException $e) {
            dd($e);
        }
    }
}
