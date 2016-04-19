<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;
use Bsmma\User;
use Bsmma\Pick;
use Bsmma\FightResult;
use Bsmma\divStrong\Transformers\PickTransformer;
use Bsmma\divStrong\Transformers\PlayerPickTransformer;

class PicksController extends ApiController
{
    public function __construct(
        Pick $pick,
        User $user,
        FightResult $fightResult,
        PickTransformer $pickTransformer,
        PlayerPickTransformer $playerPickTransformer
    )
    {
        $this->pick = $pick;
        $this->user = $user;
        $this->pickTransformer = $pickTransformer;
        $this->playerPickTransformer = $playerPickTransformer;
        $this->fightResult = $fightResult;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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

    public function getPlayerStandings($contest_id)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $standings = [];
        $picks = $this->pick->where('contest_id', $contest_id)
                    ->with('fight.fighters', 'powerUp')
                    ->orderBy('user_id')
                    ->get();

        $contestResults = $this->fightResult->where('contest_id', $contest_id)
                            ->with('powerUps')
                            ->get();

        if ( ! $picks->isEmpty() ) {
            $current_user_id = 0;
            $tally = 0;
            $picks = $picks->toArray();
            $contestResults = $contestResults->toArray();

            foreach ( $picks as $key => $pick ) {
                if ( $current_user_id !== (int)$pick['user_id'] ) {
                    if ( $current_user_id !== 0 ) {
                        $standings[] = [
                            'player_id' => $current_user_id,
                            'total' => $tally,
                        ];
                    }
                    $current_user_id = (int)$pick['user_id'];
                    $tally = 0;
                }

                foreach ( $contestResults as $result ) {
                    if ( (int)$result['fight_id'] === (int)$pick['fight_id'] ) {
                        // did player pick the winning fighter
                        if ( (int)$result['winning_fighter_id'] === (int)$pick['winning_fighter_id'] ) {
                            // was the winning fighter the favorite or the underdog
                            foreach ($pick['fight']['fighters'] as $fighter) {
                                if ( (int)$fighter['id'] === (int)$pick['winning_fighter_id'] ) {
                                    if ( (int)$fighter['pivot']['odds'] > 0 ) {
                                        $tally += 3;
                                    } else {
                                        $tally += 5;
                                    }
                                }
                            }
                        }
                        // did the player have a power-up applied to this fight
                        if ( ! is_null($pick['power_up_id']) ) {
                            // if there were no power ups achieved during the fight apply penalty
                            if ( empty($result['power_ups']) ) {
                                $tally -= (int)$pick['power_up']['penalty_points'];
                            } else {
                                // if the power up chosen was achieved add bonus points
                                $power_up_achieved = false;
                                foreach ( $result['power_ups'] as $power_up ) {
                                    if ( (int)$power_up['id'] === (int)$pick['power_up_id'] ) {
                                        $tally += (int)$pick['power_up']['bonus_points'];
                                        $power_up_achieved = true;
                                    }
                                }
                                // if the power up applied was not in the list apply penalty
                                if ( ! $power_up_achieved ) $tally -= (int)$pick['power_up']['penalty_points'];
                            }
                        }

                        if ( (int)$pick['finish_id'] === (int)$result['finish_id'] ) $tally += 5;
                        if ( (int)$pick['round'] === (int)$result['round'] ) $tally += 2;
                        if ( (int)$pick['minute'] === (int)$result['minute'] ) $tally += 1;
                    };
                }
            }
            // make sure to capture the last players total
            $standings[] = [
                'player_id' => $current_user_id,
                'total' => $tally,
            ];
        }

        $standings = array_reverse(array_values(array_sort($standings, function ($value) {
            return $value['total'];
        })));

        $data = [
            'standings' => $standings,
            'player' => $user->id
        ];

        return $this->respond(['data' => [$data]]);
    }

    public function getPlayerStandingsList($contest_id)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $standings = [];
        $picks = $this->pick->where('contest_id', $contest_id)
                    ->with('fight.fighters', 'powerUp')
                    ->orderBy('user_id')
                    ->get();

        $contestResults = $this->fightResult->where('contest_id', $contest_id)
                            ->with('powerUps')
                            ->get();

        if ( ! $picks->isEmpty() ) {
            $current_user_id = 0;
            $tally = 0;
            $picks = $picks->toArray();
            $contestResults = $contestResults->toArray();

            foreach ( $picks as $key => $pick ) {
                if ( $current_user_id !== (int)$pick['user_id'] ) {
                    if ( $current_user_id !== 0 ) {
                        $standings[] = [
                            'player_id' => $current_user_id,
                            'total' => $tally,
                        ];
                    }
                    $current_user_id = (int)$pick['user_id'];
                    $tally = 0;
                }

                foreach ( $contestResults as $result )
                {
                    if ( (int)$result['fight_id'] === (int)$pick['fight_id'] )
                    {
                        // did player pick the winning fighter
                        if ( (int)$result['winning_fighter_id'] === (int)$pick['winning_fighter_id'] )
                        {
                            // was the winning fighter the favorite or the underdog
                            foreach ($pick['fight']['fighters'] as $fighter)
                            {
                                if ( (int)$fighter['id'] === (int)$pick['winning_fighter_id'] )
                                {
                                    if ( (int)$fighter['pivot']['odds'] > 0 )
                                    {
                                        $tally += 3;
                                    } else
                                    {
                                        $tally += 5;
                                    }
                                }
                            }
                        }
                        // did the player have a power-up applied to this fight
                        if ( ! is_null($pick['power_up_id']) )
                        {
                            // if there were no power ups achieved during the fight apply penalty
                            if ( empty($result['power_ups']) )
                            {
                                $tally -= (int)$pick['power_up']['penalty_points'];
                            } else
                            {
                                // if the power up chosen was achieved add bonus points
                                $power_up_achieved = false;
                                foreach ( $result['power_ups'] as $power_up )
                                {
                                    if ( (int)$power_up['id'] === (int)$pick['power_up_id'] )
                                    {
                                        $tally += (int)$pick['power_up']['bonus_points'];
                                        $power_up_achieved = true;
                                    }
                                }
                                // if the power up applied was not in the list apply penalty
                                if ( ! $power_up_achieved ) $tally -= (int)$pick['power_up']['penalty_points'];
                            }
                        }

                        if ( (int)$pick['finish_id'] === (int)$result['finish_id'] ) $tally += 5;
                        if ( (int)$pick['round'] === (int)$result['round'] ) $tally += 2;
                        if ( (int)$pick['minute'] === (int)$result['minute'] ) $tally += 1;
                    };
                }
            }
            // make sure to capture the last players total
            $standings[] = [
                'player_id' => $current_user_id,
                'total' => $tally,
            ];
        }

        $standings = array_reverse(array_values(array_sort($standings, function ($value)
        {
            return $value['total'];
        })));

        foreach( $standings as $key => $value )
        {
            $user_info = $this->user->find($value['player_id']);
            $standings[$key]['player_name'] = $user_info->player_name;
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
