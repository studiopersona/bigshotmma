<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;
use Bsmma\ContestType;
use Bsmma\divStrong\Transformers\ContestTypeTransformer;

class ContestTypesController extends ApiController
{
	public function __construct(ContestType $contestType, ContestTypeTransformer $contestTypeTransformer)
	{
		$this->contestType = $contestType;
		$this->contestTypeTransformer = $contestTypeTransformer;
	}


	public function index()
	{
		return $this->contestTypeTransformer->transformCollection($this->contestType->orderBy('sort_order')->get()->toArray());
	}
}
