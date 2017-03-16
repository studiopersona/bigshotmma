<?php

namespace Bsmma\Http\Controllers;

use Bsmma\Contest;
use Bsmma\ContestParticipant;
use Bsmma\ContestParticipantsArchive;
use Bsmma\Http\Requests;
use Bsmma\divStrong\Reports\ContestStandings;
use Illuminate\Http\Request;

class ContestParticipantsController extends ApiController
{
    private $contest;
    private $contestStandings;
    private $contestParticipantsArchive;

    public function __construct(
        ContestParticipant $contestParticipant,
        Contest $contest,
        ContestStandings $contestStandings,
        ContestParticipantsArchive $contestParticipantsArchive
    )
    {
    	$this->contestParticipant = $contestParticipant;
        $this->contestParticipantsArchive = $contestParticipantsArchive;
        $this->contest = $contest;
        $this->contestStandings = $contestStandings;
    }

    public function getByPlayer()
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $contests = $this->contestParticipant->select('contest_id')
                        ->where('user_id', $user->id)
                        ->get();

        if ( $contests->isEmpty() )
        {
            return $this->respondNotFound('No Contest Found for Player');
        }

        foreach ($contests->toArray() as $contest )
        {
        	$contestIds[] = (int)$contest['contest_id'];
        }

        $data = ['contests' => $contestIds ];

        return response()->json($data, 200);
    }

    public function determineWinnings($contestId)
    {
        $contest = $this->contest->select('max_participants')->where('id', $contestId)->first();

        if ( is_null($contest) ) return $this->respondWithError('Could not find this contest.', 404);

        $winnings = $this->contestStandings->handle(0, $contestId);

        $start = count($winnings) + 1;

        return $this->respond(['winnings' => $winnings]);
    }

    public function playersScores($contestId)
    {
        $players = $this->contestParticipant->where('contest_id', $contestId)->with('user')->get();

        $playersScores = $players->map(function($player, $playerIndex) {
            return [
                'id' => $player->user->id,
                'totalPoints' => $this->contestParticipantsArchive->where('user_id', $player->user->id)->sum('score'),
            ];
        });

        return $this->respond($playersScores);
    }
}
