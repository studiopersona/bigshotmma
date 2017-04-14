<?php namespace Bsmma\divStrong\Scoring;

use Bsmma\PowerUp;
use Bsmma\Finish;

class FightScoring
{
	private $fighterFavorite = 3;
	private $fighterUnderdog = 5;
	private $finishNotByDecision = 10;
	private $finishByDecision = 7;
	private $correctMinute = 1;
	private $correctRound = 2;

	private $totalPoints = 0;

	private $fighterPoints;
	private $finishPoints;
	private $roundPoints;
	private $minutePoints;
	private $powerupPoints;

	private $powerUps;
	private $finishes;

	public function __construct(PowerUp $powerUp, Finish $finish)
	{
		$this->powerUp = $powerUp;
		$this->finish = $finish;

		$this->finishes = $this->finish->get()->toArray();
		$this->powerUps = $this->powerUp->get()->toArray();
	}

	public function handle($playersPicks, $fightResults, $fighters)
	{	$this->fighterPoints = 0;
		$this->roundPoints = 0;
		$this->minutePoints = 0;
		$this->finishPoints = 0;
		$this->powerupPoints = 0;
		$tally = 0;

		if ( empty($fightResults) ) return $tally;

		if ( (int)$fightResults['finish_id'] !== 4  ) {
            $this->determineFighterPoints((int)$playersPicks['winning_fighter_id'], (int)$fightResults['winning_fighter_id'], $fighters);
            $this->determineRoundPoints((int)$playersPicks['round'], (int)$fightResults['round']);
            $this->determineMinutePoints((int)$playersPicks['minute'], (int)$fightResults['minute']);
            $this->determineFinishPoints((int)$playersPicks['finish_id'], (int)$fightResults['finish_id']);
            $this->determinePowerupPoints($playersPicks['power_up_id'], $fightResults['power_ups'], $fighters);
            $tally += $this->getTotalPoints();
        } else {
            $this->fightScoring->determinePowerupPoints($playersPicks['power_up_id'], $fightResults['power_ups'], $fighters);
            $tally += $this->getTotalPoints();
        }

        return $tally;
	}

	public function determineFighterPoints($selectedFighterId, $winningFighterId, $fighters = [])
	{
		if ( (int)$selectedFighterId === (int)$winningFighterId ) {

			foreach($fighters as $fighter) {
				if ( (int)$fighter['id'] === (int)$winningFighterId ) {
					$this->fighterPoints = ( (int)$fighter['pivot']['odds'] > 0 ) ? $this->fighterUnderdog : $this->fighterFavorite;
				}
			}

		} else {
			$this->fighterPoints = 0;
		}
	}

	public function determineFinishPoints($selectedFinishId, $winningFinishId)
	{
		if ( $this->fighterPoints > 0 ) {

			if ( $selectedFinishId === $winningFinishId ) {
				$key = array_search($selectedFinishId, array_column($this->finishes, 'id'));
				$this->finishPoints = (int)$this->finishes[$key]['points'];
			} else {
				$this->finishPoints = 0;
			}

		} else {
			$this->finishPoints = 0;
		}
	}

	public function determineRoundPoints($selectedRound, $winningRound)
	{
		if ( $this->fighterPoints > 0 ) {
			$this->roundPoints = ( $selectedRound === $winningRound ) ? $this->correctRound : 0;
		} else {
			$this->roundPoints = 0;
		}
	}

	public function determineMinutePoints($selectedMinute, $winningMinute)
	{
		if ( $this->fighterPoints > 0 ) {
			$this->minutePoints = ( $selectedMinute === $winningMinute ) ? $this->correctMinute : 0;
		} else {
			$this->minutePoints = 0;
		}
	}

	public function determinePowerupPoints($selectedPowerupId, $winningPowerupIds=[], array $fighters = [])
	{

		if ( ! is_null($selectedPowerupId) ) {

			$key = array_search($selectedPowerupId, array_column($this->powerUps, 'id'));
			$found = false;

			foreach ( $winningPowerupIds as $powerUpInfo ) {
				if ($found) break;

				foreach ( $fighters as $fighter ) {
					if ( $powerUpInfo['id'] === $selectedPowerupId && $powerUpInfo['pivot']['fighter_id'] === $fighter['id'] ) {
						$found = true;
						break;
					}
				}
			}

			$this->powerupPoints = ( $found ) ?  (int)$this->powerUps[$key]['bonus_points'] : (int)$this->powerUps[$key]['penalty_points'] * -1;

		} else {
			$this->powerupPoints = 0;
		}
	}

	public function getFighterPoints()
	{
		 return $this->fighterPoints;
	}

	public function getFinishPoints()
	{
		 return $this->finishPoints;
	}

	public function getRoundPoints()
	{
		 return $this->roundPoints;
	}

	public function getMinutePoints()
	{
		 return $this->minutePoints;
	}

	public function getPowerupPoints()
	{
		 return $this->powerupPoints;
	}

	public function getTotalPoints()
	{
		 return $this->fighterPoints + $this->finishPoints + $this->roundPoints + $this->minutePoints + $this->powerupPoints;
	}
}
