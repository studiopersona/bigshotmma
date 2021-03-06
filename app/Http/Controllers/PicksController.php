<?php

namespace Bsmma\Http\Controllers;

use Bsmma\BogoPromosToUser;
use Bsmma\Contest;
use Bsmma\ContestParticipant;
use Bsmma\ContestUserBalance;
use Bsmma\Event;
use Bsmma\FightResult;
use Bsmma\Http\Requests;
use Bsmma\Pick;
use Bsmma\User;
use Bsmma\UserBalance;
use Bsmma\divStrong\Reports\PlayerRecords;
use Bsmma\divStrong\Scoring\FightScoring;
use Bsmma\divStrong\Transformers\ContestTransformer;
use Bsmma\divStrong\Transformers\PickTransformer;
use Bsmma\divStrong\Transformers\PlayerPickTransformer;
use Illuminate\Http\Request;

class PicksController extends ApiController
{
    private $bogoPromosToUser;

    public function __construct(
        Pick $pick,
        User $user,
        Contest $contest,
        Event $event,
        UserBalance $userBalance,
        ContestUserBalance $contestUserBalance,
        ContestParticipant $contestParticipant,
        FightResult $fightResult,
        PickTransformer $pickTransformer,
        PlayerPickTransformer $playerPickTransformer,
        ContestTransformer $contestTransformer,
        FightScoring $fightScoring,
        PlayerRecords $playerRecords,
        BogoPromosToUser $bogoPromosToUser
    )
    {
        $this->pick = $pick;
        $this->user = $user;
        $this->contest = $contest;
        $this->event = $event;
        $this->userBalance = $userBalance;
        $this->contestUserBalance = $contestUserBalance;
        $this->contestParticipant = $contestParticipant;
        $this->pickTransformer = $pickTransformer;
        $this->playerPickTransformer = $playerPickTransformer;
        $this->contestTransformer = $contestTransformer;
        $this->fightResult = $fightResult;
        $this->fightScoring = $fightScoring;
        $this->playerRecords = $playerRecords;
        $this->bogoPromosToUser = $bogoPromosToUser;
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

            $currentPick = $this->pick->where('user_id', $user->id)
                ->where('contest_id', $pickRow['contest_id'])
                ->where('fight_id', $pickRow['fight_id'])
                ->first();

            foreach ( $pickRow as $column => $value )
            {
                if ( (int)$value === 0 ) $value = NULL;
                $data[$column] = $value;
            }

            if ( is_null($currentPick) ) {
                $this->pick->insert($data);
            }
            else {
                $this->pick->where('id', $currentPick->id)->update($data);
            }
        }

        return $this->respond([
            'success' => true
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
                        'contest.contestType',
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

        if ( $picks->isEmpty() ) {
            $contest = $this->contest->with('event')->where('id', $contest_id)->first();
            if ( strtotime($contest->event->date_time.' -30 minutes') < strtotime(date('Y-m-d H:i:s')) ) {
                $this->setStatusCode(204);
                return $this->respondWithError('No picks made, contest is closed');
            }

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

        if ( is_null($contest) ) {
            return $this->respondNotFound('No contest was found');
        }

        $contestResults = ($contest->event->fightResults) ? $contest->event->fightResults : collect([]);

        if ( ! $playerPicks->isEmpty() && ! $contestResults->isEmpty() ) {
            $current_user_id = 0;
            $index = 0;
            $tally = 0;
            $playerPicks = $playerPicks->toArray();
            $contestResults = $contestResults->toArray();

            // cycle through all the players picks and determine scores
            foreach ( $playerPicks as $key => $pick ) {
                // if we're not still on the same user reset things
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
                    // did the player pick the winning fighter
                    if ( (int)$result['fight_id'] === (int)$pick['fight_id']  ) {
                        $tally += $this->fightScoring->handle($pick, $result, $pick['fight']['fighters']);
                        $standings[$index]['fightsReported'] += 1;
                    };
                }

                $standings[$index]['totalPoints'] = $tally;
            }
        }
        // order the standings array from highest score to lowest score
        $standings = array_reverse(array_values(array_sort($standings, function ($value) {
            return $value['totalPoints'];
        })));
        // if the request also wants user info
        if ( $withUsersInfo ) {
            foreach( $standings as $key => $value )
            {
                $user_info = $this->user->find($value['playerId']);
                $standings[$key]['player_name'] = $user_info->player_name;
                $standings[$key]['avatar'] = $user_info->avatar;
            }
        }

        $data = [
            'standings' => $standings,
            'player' => $user->id
        ];

        return $this->respond(['data' => [$data]]);
    }

    public function playersRecords($contestId)
    {
        $playerRecords = [];

        $players = $this->contestParticipant
                    ->select('user_id')
                    ->where('contest_id', $contestId)
                    ->orderBy('user_id')
                    ->get()
                    ->toArray();

        foreach( $players as $player ) {
            $playerRecords[] = [
                'record' => $this->playerRecords->getPlayerPickRecord($player['user_id'], $contestId),
                'id' => $player['user_id']
            ];
        }

        return $this->respond(['data' => $playerRecords]);
    }

    public function checkForPicks($contestId)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $count = $this->pick->where('user_id', $user->id)
                    ->where('contest_id', $contestId)
                    ->count();

        return ( $count ) ? $this->respond(['data' => ['count' => $count]]) : $this->respondNotFound('No picks were found for this user for this contest');
    }

    public function quitContest($contestId)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $this->contestParticipant
            ->where('user_id', $user->id)
            ->where('contest_id', $contestId)
            ->delete();

        $this->pick->where('user_id', $user->id)
            ->where('contest_id', $contestId)
            ->delete();

        $contest = $this->contest->select('event_id', 'entry_fee')
                    ->where('id', $contestId)
                    ->first();

        $promoCheck = $this->bogoPromosToUser->where('user_id', $user->id)
                        ->where('paid_contest_id', $contestId)
                        ->first();

        // if the entry fee wasn't free then credit the players balance
        if ( is_null($promoCheck) || is_null($promoCheck->free_contest_id) ) {
            $userBalance = $this->userBalance->create(['user_id' => $user->id, 'transaction_type_id' => 5, 'amount' => $contest->entry_fee * 100]);
            $this->contestUserBalance->create(['contest_id' => $contestId, 'user_balance_id' => $userBalance->id]);
        }

        return $this->respond(['data' => ['eventId' => $contest->event_id]]);
    }

    public function getPicksforContestByPlayer(Request $request)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        return $this->respond($this->pick->with('powerUp')->where('contest_id', $request->cid)->where('user_id', $user->id)->get()->toArray());
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
        $this->pick->where('id', $id)->delete();

        return $this->respond(['delete' => 'ok']);
    }
}
