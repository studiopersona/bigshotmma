<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;
use Bsmma\FightResult;

class FightResultsController extends ApiController
{
    public function __construct(FightResult $fightResult)
    {
    	$this->fightResult = $fightResult;
    }

    public function index($contest_id)
    {
    	$fightResults = $this->fightResult->with(['powerUps'])
    					->where('contest_id', $contest_id)
    					->get();

    	if ( $fightResults->isEmpty() )
    	{
    		return $this->respondNotFound('No Results were found');
    	}

    	return $this->respond([
            'results' => $this->fightResultTransformer->transformCollection($fightResults->toArray()),
        ]);
    }
}
