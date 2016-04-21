<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;

use Bsmma\ContestParticipant;

class ContestParticipantsController extends ApiController
{
    public function __construct(ContestParticipant $contestParticipant)
    {
    	$this->contestParticipant = $contestParticipant;
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
}
