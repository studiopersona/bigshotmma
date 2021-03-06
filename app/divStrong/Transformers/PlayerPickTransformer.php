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
        // dump($pick['contest']);

        $fighter_id = (int)$pick['winning_fighter_id'];
        $fight_id = (int)$pick['fight_id'];

        $result = $this->fightResult
                    ->with('powerUps')
                    ->where('fight_id', $pick['fight_id'])
                    ->first();

        if ( is_null($result) ) {
            $result = [];
        }
        else {
            $result = $result->toArray();
        }

        foreach ( $pick['contest']['event']['fights'] as $fight )
        {
            if ( $fight['id'] === $fight_id ) {
                $this_fight = $fight;
                break;
            }
        }

        $this->fightScoring->handle($pick, $result, $this_fight['fighters']);

    	foreach ( $this_fight['fighters'] as $fighter )
    	{
    		if ( $fighter['id'] === $fighter_id ) {
    			$odds = $fighter['pivot']['odds'];
    			break;
    		}
    	}

        return [
            // 'picks' => $pick,
            'contest' => [
                'id' => $pick['contest']['id'],
                'buy_in' => $pick['contest']['entry_fee'],
                'max_participants' => $pick['contest']['max_participants'],
                'total_participants' => count($pick['contest']['users']),
                'prize_pool' => count($pick['contest']['users']) * $pick['contest']['entry_fee'],
                'contest_type_id' => $pick['contest']['contest_type_id'],
                'contest_type_name' => $pick['contest']['contest_type']['contest_type_name'],
            ],
            'event' => [
            	'event_short_name' => $pick['contest']['event']['event_short_name'],
                'event_closed' => ( strtotime($pick['contest']['event']['date_time'].' -30 minutes') < strtotime(date('Y-m-d H:i:s')) ) ?: false
            ],
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
            'minute' => [
                'selected' => (int)$pick['minute'],
                // make this the actual points achieved
                'points' => $this->fightScoring->getMinutePoints()
            ],
        	'pick_id' => $pick['id'],
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
			'round' => [
                'selected' => (int)$pick['round'],
                // make this the actual points achieved
                'points' => $this->fightScoring->getRoundPoints(),
            ],
            'totalPoints' => $this->fightScoring->getTotalPoints(), // make this the total points achieved
        ];
    }
}