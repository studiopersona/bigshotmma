<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;
use Bsmma\Contest;
use Bsmma\ContestParticipant;
use Bsmma\User;
use Bsmma\divStrong\Transformers\ContestTransformer as ContestTransformer;

class ContestsController extends ApiController
{

    public function __construct(
        User $user,
        Contest $contest,
        ContestParticipant $contestParticipant,
        ContestTransformer $contestTransformer)
    {
        $this->user = $user;
        $this->contest = $contest;
        $this->contestParticipant = $contestParticipant;
        $this->contestTransformer = $contestTransformer;
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

    private function getContests($playerId)
    {
        $query = $this->contest->with([
                        'event',
                        'contestType',
                        'event.fights.fighters',
                    ])
                    ->where('deadline', '>', date('Y-m-d H:i:s'));

        if ( is_null($playerId) )
        {
          $contests = $query->get();
        } else {
            $contests = $query->with(['users'])->whereHas('users', function($q) use ($playerId) {
                $q->where('id', (int)$playerId);
            })->get();
        }

        return $contests;
    }
}
