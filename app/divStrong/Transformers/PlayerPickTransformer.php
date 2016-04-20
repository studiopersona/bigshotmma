<?php namespace Bsmma\divStrong\Transformers;

class PlayerPickTransformer extends Transformer {

	public function transform($pick)
    {
    	$fighter_id = (int)$pick['winning_fighter_id'];
    	$fight_id = (int)$pick['fight_id'];

    	foreach ( $pick['contest']['event']['fights'] as $fight )
    	{
    		if ( $fight['id'] === $fight_id ) {
    			$this_fight = $fight;
    			break;
    		}
    	}

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
			'round' => (int)$pick['round'],
			'minute' => (int)$pick['minute'],
			'fighter' => [
				'winning_fighter_id' => $pick['winning_fighter_id'],
            	'fighter_id' => $pick['fighter']['id'],
				'odds' => $odds,
				'name' => $pick['fighter']['firstname'].' '.$pick['fighter']['lastname'],
				'image' => $pick['fighter']['fighter_image_name'],
            ],
			'finish' => [
				'finish_id' => (int)$pick['finish_id'],
				'finish_type' => $pick['finish']['finish_name'],
				'finish_points' => $pick['finish']['points'],
			],
			'power_up' => [
				'power_up_id' => $pick['power_up_id'],
				'power_up_name' => $pick['power_up']['power_up_name'],
				'bonus_points' => $pick['power_up']['bonus_points'],
				'penalty_points' => $pick['power_up']['penalty_points'],
				'color' => $pick['power_up']['power_up_color'],
                'image' => $pick['power_up']['power_up_image_name'],
			],
        ];
    }
}