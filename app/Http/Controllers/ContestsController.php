<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;
use Bsmma\Contest;
use Bsmma\ContestParticipant;
use Bsmma\User;
use Bsmma\divStrong\Transformers\ContestTransformer as ContestTransformer;
use Bsmma\divStrong\Transformers\PlayerTransformer as PlayerTransformer;
use Bsmma\divStrong\Transformers\FightTransformer as FightTransformer;

class ContestsController extends ApiController
{

    public function __construct(
        User $user,
        Contest $contest,
        ContestParticipant $contestParticipant,
        ContestTransformer $contestTransformer,
        PlayerTransformer $playerTransformer,
        FightTransformer $fightTransformer
    )
    {
        $this->user = $user;
        $this->contest = $contest;
        $this->contestParticipant = $contestParticipant;
        $this->contestTransformer = $contestTransformer;
        $this->playerTransformer = $playerTransformer;
        $this->fightTransformer = $fightTransformer;

        $this->middleware('jwt.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($playerId = null )
    {
        $contests = $this->getContests($playerId);

        if ( $contests->isEmpty() )
        {
            return $this->respondNotFound('No contests found');
        }

        return $this->respond([
            'contests' => $this->contestTransformer->transformCollection($contests->toArray()),
        ]);
    }

    public function eventLimited($eventId)
    {
        $contests = $this->getContests(NULL, $eventId);

        if ( $contests->isEmpty() )
        {
            return $this->respondNotFound('No contests found');
        }

        return $this->respond([
            'contests' => $this->contestTransformer->transformCollection($contests->toArray()),
        ]);
    }

    public function getParticipants($contest_id)
    {
        $participants = $this->contest->with(['event', 'users', 'contestType'])
                            ->where('id', $contest_id)
                            ->get();

        if ( $participants->isEmpty() )
        {
            return $this->respondNotFound('Contest Not Found');
        }

        $participants = $participants->toArray();

        foreach($participants[0]['users'] as $key => $participant)
        {
            $participants[0]['users'][$key]['record'] = $this->getPlayerRecord($participant['id']);
        }

        return $this->respond([
            'participants' => $this->playerTransformer->transformCollection($participants),
        ]);
    }

    public function getFights($contest_id)
    {
        $fights = $this->contest->with([
                        'event',
                        'event.fights',
                        'event.fights.fighters',
                        'event.fights.fighters.nationality'
                    ])
                    ->where('id', $contest_id)
                    ->get();

        if ( $fights->isEmpty() )
        {
            return $this->respondNotFound('No Fights Found for this Contest');
        }

        return $this->respond([
            'fights' => $this->fightTransformer->transformCollection($fights->toArray()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // will show the contest lobby of  a specific contest
        $contest = $query = $this->contest->with([
                        'event',
                        'contestType',
                        'event.fights.fighters',
                        'users'
                    ])
                    ->where('id', $id)
                    ->get();

        if ( $contest->isEmpty() )
        {
            return $this->respondNotFound('Contest not found');
        }

        return $this->respond([
            'contest' => $this->contestTransformer->transformCollection($contest->toArray()),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function getContests($playerId, $eventId = NULL)
    {
        $query = $this->contest->with([
                        'contestType',
                        'event.fights.fighters',
                        'event',
                        'users'
                    ])
                    ->where('deadline', '>', date('Y-m-d H:i:s', strtotime('-1 week')));

        if ( ! is_null($playerId) )
        {
            $query->whereHas('users', function($q) use ($playerId) {
                $q->where('id', (int)$playerId);
            });
        }

        if ( ! is_null($eventId) )
        {
            $query->whereHas('event', function($q) use ($eventId) {
                $q->where('id', (int)$eventId);
            });
        }

        return $query->get();
    }

    private function getTotalParticipants($contestId)
    {
        return $this->contestParticipant->where('contest_id', $contestId)->count();
    }

    private function getPlayerRecord($user_id)
    {
        return [
            'wins' => 0,
            'losses' => 0,
            'win_percentage' => 0,
        ];
    }
}
