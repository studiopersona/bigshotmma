<?php

namespace Bsmma\Http\Controllers;

use Bsmma\Http\Requests;
use Bsmma\divStrong\Promotions\BogoPromotion;
use Illuminate\Http\Request;

class BogoPromoController extends ApiController
{
	private $bogoPromo;


	public function __construct(BogoPromotion $bogoPromo)
	{
		$this->bogoPromo = $bogoPromo;
	}

	public function validateCode(Request $request)
	{
    	$user = \JWTAuth::parseToken()->authenticate();

		return $this->respond($this->bogoPromo->validateCode($request, $user));
	}
}
