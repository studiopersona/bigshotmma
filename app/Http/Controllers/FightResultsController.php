<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;
use Bsmma\FightResult;
use Bsmma\Event;
use Bsmma\Contest;
use Bsmma\Pick;
use Bsmma\User;
use Bsmma\divStrong\Transformers\FightResultTransformer as FightResultTransformer;

class FightResultsController extends ApiController
{
    public function __construct(
        FightResult $fightResult,
        FightResultTransformer $fightResultTransformer,
        Pick $pick,
        User $user,
        Contest $contest,
        Event $event
    )
    {
    	$this->fightResult = $fightResult;
        $this->fightResultTransformer = $fightResultTransformer;
        $this->pick = $pick;
        $this->user = $user;
        $this->event = $event;
        $this->contest = $contest;
    }

    public function getByEvent($event_id)
    {
    	$event = $this->event->with([
                                'fightResults',
                                'fightResults.fight',
                                'fightResults.fight.fighters',
                                'fightResults.powerUps',
                                'fightResults.finish'
                            ])
                            ->where('id', $event_id)
                            ->first();

        if ( is_null($event) )
        {
            return $this->respondNotFound('No Event was found');
        }

        $fightResults = ($event->fightResults) ? $event->fightResults : collect([]);

    	if ( $fightResults->isEmpty() )
    	{
    		return $this->respondNotFound('No Results were found');
    	}

    	return $this->respond([
            'results' => $this->fightResultTransformer->transformCollection($fightResults->toArray()),
            'event' => ['event_name' => $event->event_short_name, 'event_id' => $event->id],
        ]);
    }

    public function getByContestId($contest_id)
    {
        $contest = $this->contest->with([
                        'event',
                        'event.fightResults',
                        'event.fightResults.fight',
                        'event.fightResults.fight.fighters',
                        'event.fightResults.powerUps',
                        'event.fightResults.finish'
                    ])
                    ->where('id', $contest_id)
                    ->first();

        if ( is_null($contest) )
        {
            return $this->respondNotFound('No contest was found');
        }

        $fightResults = ($contest->event->fightResults) ? $contest->event->fightResults : collect([]);

        if ( $fightResults->isEmpty() )
        {
            return $this->respondNotFound('No Results were found');
        }

        return $this->respond([
            'results' => $this->fightResultTransformer->transformCollection($fightResults->toArray()),
            'event' => ['event_name' => $contest->event->event_short_name, 'event_id' => $contest->event->id],
            'contest' => ['contest_id' => $contest->id],
        ]);
    }
}
