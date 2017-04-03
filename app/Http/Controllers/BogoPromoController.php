<?php

namespace Bsmma\Http\Controllers;

use Bsmma\Http\Requests;
use Bsmma\divStrong\Promotions\BogoPromotion;
use Illuminate\Http\Request;

class BogoPromoController extends ApiController
{
	private $bogoPromo;
	private $user;


	public function __construct(BogoPromotion $bogoPromo)
	{
		$this->bogoPromo = $bogoPromo;
    	$this->user = \JWTAuth::parseToken()->authenticate();
	}

	public function validateCode(Request $request)
	{
		return $this->respond($this->bogoPromo->validateCode($request, $this->user));
	}

	public function checkForActiveCode()
	{
		return $this->respond($this->bogoPromo->checkForActiveCode($this->user->id));
	}

	public function updateStage(Request $request)
	{
		$method = 'moveToStage'.$request->stage;

		$this->bogoPromo->{$method}($request, $this->user);
	}

	public function backupStage(Request $request)
	{
		$this->bogoPromo->backupStage($request);
	}
}
