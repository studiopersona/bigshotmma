<?php

use Illuminate\Support\Collection;

function calculatePlayersBalance($playerId)
{
	$credits = \Bsmma\UserBalance::where('user_id', $playerId)
                    ->whereIn('transaction_type_id', [2, 4, 5, 6])
                    ->sum('amount');

    $debits = \Bsmma\UserBalance::where('user_id', $playerId)
                ->whereIn('transaction_type_id', [1, 3])
                ->sum('amount');

    return $credits - $debits;
}

function calculatePlayersTotalPoints($playerId)
{
	return \Bsmma\ContestParticipantsArchive::where('user_id', $playerId)->sum('score');
}

function determineBogoStatus($data)
{
	if ( !is_null($data->completed_paid_contest_on) ) {
		return 'Free Contest Redeemed';
	}
	elseif ( !is_null($data->entered_free_contest_on) ) {
		return 'Free Contest Entered';
	}
	elseif ( !is_null($data->completed_paid_contest) ) {
		return 'Free Contest Unlocked';
	}
	elseif ( !is_null($data->entered_paid_contest_on) ) {
		return 'Entered Eligible Paid Contest';
	}
	else {
		return 'Awaiting Eligible Contest Entry';
	}
}