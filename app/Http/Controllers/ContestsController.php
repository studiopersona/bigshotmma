<?php

namespace Bsmma\Http\Controllers;

use Bsmma\Contest;
use Bsmma\ContestParticipant;
use Bsmma\ContestParticipantsArchive;
use Bsmma\ContestUserBalance;
use Bsmma\Http\Requests;
use Bsmma\User;
use Bsmma\UserBalance;
use Bsmma\divStrong\Promotions\BogoPromotion;
use Bsmma\divStrong\Transformers\ContestTransformer as ContestTransformer;
use Bsmma\divStrong\Transformers\FightTransformer as FightTransformer;
use Bsmma\divStrong\Transformers\PlayerTransformer as PlayerTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContestsController extends ApiController
{
    private $bogoPromo;


    public function __construct(
        User $user,
        Contest $contest,
        ContestParticipant $contestParticipant,
        ContestTransformer $contestTransformer,
        PlayerTransformer $playerTransformer,
        FightTransformer $fightTransformer,
        UserBalance $userBalance,
        ContestUserBalance $contestUserBalance,
        ContestParticipantsArchive $contestParticipantsArchive,
        BogoPromotion $bogoPromo
    )
    {
        $this->user = $user;
        $this->contest = $contest;
        $this->contestParticipant = $contestParticipant;
        $this->contestParticipantsArchive = $contestParticipantsArchive;
        $this->contestTransformer = $contestTransformer;
        $this->playerTransformer = $playerTransformer;
        $this->fightTransformer = $fightTransformer;
        $this->userBalance = $userBalance;
        $this->contestUserBalance = $contestUserBalance;
        $this->bogoPromo = $bogoPromo;

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

        if ( $contests->isEmpty() ) return $this->respondNotFound('No contests found');

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
            $playersScore = $this->getPlayersScore($participant['id']);
            $participants[0]['users'][$key]['overallPoints'] = (is_null($playersScore)) ? 0 : $playersScore;
        }

        return $this->respond([
            'participants' => $this->playerTransformer->transformCollection($participants),
        ]);
    }

    private function getPlayersScore($userId)
    {
        return $this->contestParticipantsArchive->where('user_id', $userId)->sum('score');
    }

    public function enterPlayer($contest_id)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $hasPlayerEntered = $this->hasPlayerEntered($contest_id, $user);

        if ( $hasPlayerEntered ) return $this->respondWithError('Player already entered this contest.');

        $contest = $this->contest->select('entry_fee')
                    ->where('id', $contest_id)
                    ->first();

        if ( is_null($contest) ) return $this->respondNotFound('The contest could not be found');

        // grab the bogo promo if the player has one
        $bogoPromoCode = $this->bogoPromo->checkForActiveCode($user->id);

        dump($bogoPromoCode);

        DB::beginTransaction();

        try {
            $this->contestParticipant->create(['contest_id'=> $contest_id, 'user_id' => $user->id]);

            // if the players bogo promo is at stage 3 (unlocked free entry) and the entry fee is the same as the paid contest
            // entry fee, then waive the entry fee

            if ( $bogoPromoCode['promo']['status']['stage'] === 4 && ($bogoPromoCode['promo']['paidContestEntryFee'] === $contest->entry_fee) ) {

                $userBalance = $this->userBalance->create(['user_id' => $user->id, 'transaction_type_id' => 1, 'amount' => 0]);
             } else {
                $userBalance = $this->userBalance->create(['user_id' => $user->id, 'transaction_type_id' => 1, 'amount' => $contest->entry_fee * 100]);
            }

            $this->contestUserBalance->create(['contest_id' => $contest_id, 'user_balance_id' => $userBalance->id, 'is_entry' => 1]);
        }
        catch (\Exception $e) {
            dump($e);
            DB::rollback();
            return $this->respondWithError('There was a problem entering the contest');
        }

        DB::commit();

        return $this->respond([
            'success' => true,
            'balance' => $this->getUserBalance($user->id)
        ]);
    }

    public function hasPlayerEntered($contest_id, $user = NULL)
    {
        $player = (is_null($user)) ? \JWTAuth::parseToken()->authenticate() : $user;

        $check = $this->contestParticipant->where([
                        'user_id' => $player->id,
                        'contest_id' => $contest_id
                    ])
                    ->first();

        if ( is_null($check) ) return ( is_null($user) ) ? $this->respond(['hasPlayerEntered' => false]) : false;

        return ( is_null($user) ) ? $this->respond(['hasPlayerEntered' => true]) : true;
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

    public function getPlayerEntries($playerId)
    {
        $contests = $this->getContests($playerId, NULL, false);

        if ( $contests->isEmpty() ) return $this->respondNotFound('No contests found');

        $sorted = $contests->sortByDesc('event.date_time')->values();

        return $this->respond([
            'contests' => $this->contestTransformer->transformCollection($sorted->toArray()),
        ]);
    }

    protected function getContests($playerId = NULL, $eventId = NULL, $useDeadline = true)
    {
        $query = $this->contest->with([
                        'contestType',
                        'event.fights.fighters',
                        'event',
                        'contestParticipants',
                        'users'
                    ]);

        if ( $useDeadline ) $query->where('deadline', '>', date('Y-m-d H:i:s', strtotime('-1 week')));

        if ( ! is_null($playerId) )
        {
            $query->whereHas('contestParticipants', function($q) use ($playerId) {
                $q->where('user_id', (int)$playerId);
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

    protected function getTotalParticipants($contestId)
    {
        return $this->contestParticipant->where('contest_id', $contestId)->count();
    }

    protected function getPlayerRecord($user_id)
    {
        return [
            'wins' => 0,
            'losses' => 0,
            'win_percentage' => 0,
        ];
    }

    protected function getUserBalance($userId)
    {
        $credits = $this->userBalance->where('user_id', $userId)
                    ->whereIn('transaction_type_id', [2, 4, 5, 6])
                    ->sum('amount');

        $debits = $this->userBalance->where('user_id', $userId)
                    ->whereIn('transaction_type_id', [1, 3])
                    ->sum('amount');

        return ($credits - $debits)/100;
    }
}
