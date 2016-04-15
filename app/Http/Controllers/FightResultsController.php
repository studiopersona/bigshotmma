<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;
use Bsmma\FightResult;
use Bsmma\divStrong\Transformers\FightResultTransformer as FightResultTransformer;

class FightResultsController extends ApiController
{
    public function __construct(FightResult $fightResult, FightResultTransformer $fightResultTransformer)
    {
    	$this->fightResult = $fightResult;
        $this->fightResultTransformer = $fightResultTransformer;
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
