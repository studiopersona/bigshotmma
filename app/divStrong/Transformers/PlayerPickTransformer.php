<?php namespace Bsmma\divStrong\Transformers;

use Bsmma\divStrong\Scoring\FightScoring;
use Bsmma\FightResult;

class PlayerPickTransformer extends Transformer {

    public function __construct(FightScoring $fightScoring, FightResult $fightResult)
    {
        $this->fightScoring = $fightScoring;
        $this->fightResult = $fightResult;
    }

	public function transform($pick)
    {
        $fighter_id = (int)$pick['winning_fighter_id'];
        $fight_id = (int)$pick['fight_id'];

        $result = $this->fightResult
                    ->with('powerUps')
                    ->where('fight_id', $pick['fight_id'])
                    ->first();

        foreach ( $pick['contest']['event']['fights'] as $fight )
        {
            if ( $fight['id'] === $fight_id ) {
                $this_fight = $fight;
                break;
            }
        }
        // determine the points acheived here
        $this->fightScoring->determineFighterPoints($pick['winning_fighter_id'], $result['winning_fighter_id'], $this_fight['fighters']);
        $this->fightScoring->determineFinishPoints($pick['finish_id'], $result['finish_id']);
        $this->fightScoring->determinePowerupPoints($pick['power_up_id'], $result['powerUps']->toArray());
        $this->fightScoring->determineRoundPoints($pick['round'], $result['round']);
        $this->fightScoring->determineMinutePoints($pick['minute'], $result['minute']);

    	foreach ( $this_fight['fighters'] as $fighter )
    	{
    		if ( $fighter['id'] === $fighter_id ) {
    			$odds = $fighter['pivot']['odds'];
    			break;
    		}
    	}

        return [
            // 'picks' => $pick,
            'event' => [
            	'event_short_name' => $pick['contest']['event']['event_short_name'],
            ],
            'contest' => [
                'id' => $pick['contest']['id'],
            	'buy_in' => $pick['contest']['entry_fee'],
            	'max_participants' => $pick['contest']['max_participants'],
            	'total_participants' => count($pick['contest']['users']),
            	'prize_pool' => count($pick['contest']['users']) * $pick['contest']['entry_fee'],
            ],
        	'pick_id' => $pick['id'],
			'fight_id' => $pick['fight_id'],
            'fighter' => [
                'winning_fighter_id' => $pick['winning_fighter_id'],
                'fighter_id' => $pick['fighter']['id'],
                'odds' => $odds,
                'name' => $pick['fighter']['firstname'].' '.$pick['fighter']['lastname'],
                'image' => $pick['fighter']['fighter_image_name'],
                'points' => $this->fightScoring->getFighterPoints(),
                // add the fighter points achieved
            ],
            'finish' => [
                'finish_id' => (int)$pick['finish_id'],
                'finish_type' => $pick['finish']['finish_name'],
                // make this the actual points achieved not the poin
                'points' => $this->fightScoring->getFinishPoints(),
            ],
			'round' => [
                'selected' => (int)$pick['round'],
                // make this the actual points achieved
                'points' => $this->fightScoring->getRoundPoints(),
            ],
			'minute' => [
                'selected' => (int)$pick['minute'],
                // make this the actual points achieved
                'points' => $this->fightScoring->getMinutePoints()
            ],
			'power_up' => [
				'power_up_id' => $pick['power_up_id'],
				'power_up_name' => $pick['power_up']['power_up_name'],
				'bonus_points' => $pick['power_up']['bonus_points'],
				'penalty_points' => $pick['power_up']['penalty_points'],
				'color' => $pick['power_up']['power_up_color'],
                'image' => $pick['power_up']['power_up_image_name'],
                // make this the actual points achieved
                'points' => $this->fightScoring->getPowerupPoints(),
			],
            'totalPoints' => $this->fightScoring->getTotalPoints(), // make this the total points achieved
        ];
    }
}