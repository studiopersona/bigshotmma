<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;
use Bsmma\Finish;
use Bsmma\divStrong\Transformers\FinishTransformer;

class FinishesController extends ApiController
{
    public function __construct(Finish $finish, FinishTransformer $finishTransformer)
    {
    	$this->finish = $finish;
    	$this->finishTransformer = $finishTransformer;
    }

    public function index()
    {
    	return $this->finishTransformer->transformCollection($this->finish->orderBy('sort_order')->get()->toArray());
    }
}
