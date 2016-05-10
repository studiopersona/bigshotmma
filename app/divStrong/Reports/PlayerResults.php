<?php namespace Bsmma\divStrong\Reports;

use Bsmma\divStrong\Scoring\FightScoring;

class PlayerResults
{
	public function fighter($selectedFighterId, $winningFighterId, $fighters)
	{
		$results = [];

		foreach($fighters as $fighter) {
			if ( (int)$fighter['id'] === $winningFighterId ) {
				$results['favorite'] = ( (int)$fighter['pivot']['odds'] < 0 );
			}
		}
		if ( $selectedFighterId === $winningFighterId )
			$this->fightScoring->determineFighterPoints($selectedFighterId, $winningFighterId, $fighters);
			$results['points'] = $this->fightScoring->getFighterPoints();



			$results['correct'] = true;
		} else {
			$results = [
				'correct' => false,

			]
		}
	}

	public function finish($selectedFinishId, $winningFinishId)
	{
		$this->fightScoring->determineFinishPoints($selectedFinishId, $winningFinishId);
	}

	public function round()
	{
		$this->fightScoring->determineRoundPoints($selectedRound, $winningRound);
	}

	public function minute()
	{
		$this->fightScoring->determineMinutePoints($selectedMinute, $winningMinute);
	}

	public function powerup()
	{
		$this->fightScoring->determinePowerupPoints($selectedPowerupId, $winningPowerupIds);
	}
}