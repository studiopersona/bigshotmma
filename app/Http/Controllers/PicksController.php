<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;
use Bsmma\User;
use Bsmma\Pick;
use Bsmma\Contest;
use Bsmma\FightResult;
use Bsmma\ContestParticipant;
use Bsmma\divStrong\Transformers\PickTransformer;
use Bsmma\divStrong\Transformers\PlayerPickTransformer;
use Bsmma\divStrong\Transformers\ContestTransformer;
use Bsmma\divStrong\Scoring\FightScoring;

class PicksController extends ApiController
{
    public function __construct(
        Pick $pick,
        User $user,
        Contest $contest,
        ContestParticipant $contestParticipant,
        FightResult $fightResult,
        PickTransformer $pickTransformer,
        PlayerPickTransformer $playerPickTransformer,
        ContestTransformer $contestTransformer,
        FightScoring $fightScoring
    )
    {
        $this->pick = $pick;
        $this->user = $user;
        $this->contest = $contest;
        $this->contestParticipant = $contestParticipant;
        $this->pickTransformer = $pickTransformer;
        $this->playerPickTransformer = $playerPickTransformer;
        $this->contestTransformer = $contestTransformer;
        $this->fightResult = $fightResult;
        $this->fightScoring = $fightScoring;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // !!!!! getAuthUser is not complete !!!!
        $picks = $this->pick->with([
                    'contest.event.fights.fighters',
                    'fight',
                    'finish',
                    'fighter',
                    'powerUp',
                ])
                ->where('user_id', $this->user->getAuthUser())
                ->get();

        return $this->respond([
            'picks' => $this->pickTransformer->transformCollection($picks->toArray()),
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
        $user = \JWTAuth::parseToken()->authenticate();
        $requestData = $request->all();

        $data = [];
        $data['user_id'] = $user->id;
        $data['created_at'] = date('Y-m-d h:i:s');
        $data['updated_at'] = date('Y-m-d h:i:s');

        foreach ( $requestData['picks'] as $pickRow )
        {
            foreach ( $pickRow as $column => $value )
            {
                if ( (int)$value === 0 ) $value = NULL;
                $data[$column] = $value;
            }

            $this->pick->insert($data);
        }

        $this->contestParticipant->create(['contest_id'=> $requestData['picks'][0]['contest_id'], 'user_id' => $user->id]);

        return $this->respond([
            'success' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function showCard($contest_id)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $picks = $this->pick->with([
                        'contest',
                        'contest.event',
                        'contest.users',
                        'contest.event.fights.fighters',
                        'fight',
                        'fighter',
                        'finish',
                        'powerUp',
                    ])
                    ->where('contest_id', $contest_id)
                    ->where('user_id', $user->id)
                    ->get();

        if ( $picks->isEmpty() )
        {
            return $this->respondNotFound('No Picks Found');
        }

        return $this->respond([
            'picks' => $this->playerPickTransformer->transformCollection($picks->toArray()),
        ]);
    }

    public function getStandings($contest_id, $withUsersInfo = 0)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $standings = [];
        $playerPicks = $this->pick->where('contest_id', $contest_id)
                    ->with('fight.fighters', 'powerUp')
                    ->orderBy('user_id')
                    ->get();

        $contestResults = $this->fightResult->where('contest_id', $contest_id)
                            ->with('powerUps')
                            ->get();

        if ( ! $playerPicks->isEmpty() ) {
            $current_user_id = 0;
            $index = 0;
            $tally = 0;
            $playerPicks = $playerPicks->toArray();
            $contestResults = $contestResults->toArray();

            foreach ( $playerPicks as $key => $pick ) {
                if ( (int)$current_user_id !== (int)$pick['user_id'] ) {
                    $current_user_id = (int)$pick['user_id'];
                    $tally = 0;
                    $index += 1;
                    $standings[$index] = [
                        'playerId' => (int)$pick['user_id'],
                        'fightsReported' => 0
                    ];
                }

                foreach ( $contestResults as $result ) {
                    if ( (int)$result['fight_id'] === (int)$pick['fight_id'] ) {
                        if ( (int)$result['winning_fighter_id'] === (int)$pick['winning_fighter_id'] ) {
                            $this->fightScoring->determineFighterPoints((int)$result['winning_fighter_id'], (int)$pick['winning_fighter_id'], $pick['fight']['fighters']);
                            $this->fightScoring->determinePowerupPoints($pick['power_up_id'], $result['power_ups']);
                            $this->fightScoring->determineRoundPoints((int)$pick['round'], (int)$result['round']);
                            $this->fightScoring->determineMinutePoints((int)$pick['minute'], (int)$result['minute']);
                            $this->fightScoring->determineFinishPoints((int)$pick['finish_id'], (int)$result['finish_id']);
                            $tally += $this->fightScoring->getTotalPoints();
                        }
                        $standings[$index]['fightsReported'] += 1;
                    };
                }

                $standings[$index]['totalPoints'] = $tally;
            }
        }

        $standings = array_reverse(array_values(array_sort($standings, function ($value) {
            return $value['totalPoints'];
        })));

        if ( $withUsersInfo ) {
            foreach( $standings as $key => $value )
            {
                $user_info = $this->user->find($value['playerId']);
                $standings[$key]['player_name'] = $user_info->player_name;
            }
        }

        $data = [
            'standings' => $standings,
            'player' => $user->id
        ];

        return $this->respond(['data' => [$data]]);
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
}
