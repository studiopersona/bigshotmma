<?php namespace Bsmma\divStrong\Reports;

use Bsmma\User;
use Bsmma\FightResult;
use Bsmma\Pick;
use Bsmma\ContestParticipant;

class PlayerRecords
{
	public function __construct(
		User $user,
		FightResult $fightResult,
		Pick $pick,
		ContestParticipant $contestParticipant
	)
	{
		$this->user = $user;
		$this->fightResult = $fightResult;
		$this->pick = $pick;
		$this->contestParticipant = $contestParticipant;
	}

	public function getPlayerPickRecord($playerId, $contestId)
	{
		$record = [];

		$contestsList = $this->contestParticipant
							->select('contest_id')
							->where('user_id', $playerId)
							->orderBy('contest_id')
							->get()
							->toArray();

		$picks = $this->pick->where('user_id', $playerId)
					->with('fightResult')
					->whereIn('contest_id', $contestsList)
					->orderBy('contest_id')
					->get();

		$record['totalPicks'] = $picks->count();
		$correctPicks = 0;

		if ( ! $picks->isEmpty() ) {
			$picks->each(function($pick, $index) use ($record, &$correctPicks) {
				if ( (int)$pick->winning_fighter_id === (int)$pick->fightResult->winning_fighter_id ) $correctPicks += 1;
			});
			$record['correctPicks'] = $correctPicks;
			$record['incorrectPicks'] = $record['totalPicks'] - $correctPicks;
		} else {
			$record = [
				'totalPicks' => 0,
				'correctPicks' => 0,
				'incorrectPicks' => 0,
			];
		}

		return $record;
	}
}