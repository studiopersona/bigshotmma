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

function determineBogoStatus($data, array $validEntryFees)
{
	if ( $data->is_complete ) {
		return [
			'stage' => 5,
			'display' => 'Free Contest Redeemed',
		];
	}
	elseif ( !is_null($data->free_contest_id) ) {
		return [
			'stage' => 4,
			'display' => 'You have entered a contest for free.',
		];
	}
	elseif ( !is_null($data->completed_paid_contest_on) ) {
		return [
			'stage' => 3,
			'display' => 'You have a free $'.$data->paid_contest_entry_fee.' entry available.',
		];
	}
	elseif ( !is_null($data->paid_contest_id) ) {
		return [
			'stage' => 2,
			'display' => 'You are entered in an eligible paid contest.',
		];
	}
	else {
		return [
			'stage' => 1,
			'display' => 'Enter an eligible contest ($'.implode(', $', $validEntryFees).')',
		];
	}
}