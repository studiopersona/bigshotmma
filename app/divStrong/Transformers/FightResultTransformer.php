<?php namespace Bsmma\divStrong\Transformers;

class FightResultTransformer extends Transformer {

	public function transform($fightResult)
    {
    	$timeAdjustment = ((int)$fightResult['round'] - 1) * 5;
    	foreach ( $fightResult['fight']['fighters'] as $index => $fighter ) {
    		$fightResult['fight']['fighters'][$index]['powerupsAchieved'] = array();
    		foreach ( $fightResult['power_ups'] as $key => $powerUpAchieved ) {
    			if ( (int)$powerUpAchieved['pivot']['fighter_id'] === (int)$fighter['id'] ) {
    				$fightResult['fight']['fighters'][$index]['powerupsAchieved'][] = [
    					'id' => $powerUpAchieved['id'],
    					'power_up_image_name' => $powerUpAchieved['power_up_image_name'],
    					'power_up_color' => $powerUpAchieved['power_up_color'],
    					'power_up_name' => $powerUpAchieved['power_up_name'],
    				];
    			}
    		}
    	}

        return [
        	'fightResults' => [
	        	'id' => $fightResult['id'],
	        	'fight_id' => $fightResult['fight_id'],
	        	'winning_fighter_id' => $fightResult['winning_fighter_id'],
  				'finish_id' => $fightResult['finish_id'],
	        	'round' => $fightResult['round'],
	        	'minute' => $fightResult['minute'],
	        	'total_time' => $fightResult['total_time'],
	        	'finish' => [
	        		'id' => $fightResult['finish']['id'],
	        		'finish_name' => $fightResult['finish']['finish_name'],
	        		'abbr' => $fightResult['finish']['abbr'],
	        		'points' => $fightResult['finish']['points'],
	        	],
	        	'fight' => [
	        		'id' => $fightResult['fight']['id'],
	        		'event_id' => $fightResult['fight']['event_id'],
	        		'rounds' => $fightResult['fight']['rounds'],
	        		'fighters' => [
	        			[
	        				'id' => $fightResult['fight']['fighters'][0]['id'],
	        				'firstname' => $fightResult['fight']['fighters'][0]['firstname'],
	        				'lastname' => $fightResult['fight']['fighters'][0]['lastname'],
	        				'fighter_image_name' => $fightResult['fight']['fighters'][0]['fighter_image_name'],
	        				'gender' => $fightResult['fight']['fighters'][0]['gender'],
	        				'height_ft' => $fightResult['fight']['fighters'][0]['height_ft'],
	        				'height_in' => $fightResult['fight']['fighters'][0]['height_in'],
	        				'weight_lbs' => $fightResult['fight']['fighters'][0]['weight_lbs'],
	        				'wins' => $fightResult['fight']['fighters'][0]['wins'],
	        				'loses' => $fightResult['fight']['fighters'][0]['loses'],
	        				'draws' => $fightResult['fight']['fighters'][0]['draws'],
	        				'pivot' => [
	        					'fight_id' => $fightResult['fight']['fighters'][0]['pivot']['fight_id'],
	        					'fighter_id' => $fightResult['fight']['fighters'][0]['pivot']['fighter_id'],
	        					'odds' => $fightResult['fight']['fighters'][0]['pivot']['odds']
	        				],
	        				'powerupsAchieved' => $fightResult['fight']['fighters'][0]['powerupsAchieved']
	        			],
	        			[
	        				'id' => $fightResult['fight']['fighters'][1]['id'],
	        				'firstname' => $fightResult['fight']['fighters'][1]['firstname'],
	        				'lastname' => $fightResult['fight']['fighters'][1]['lastname'],
	        				'fighter_image_name' => $fightResult['fight']['fighters'][1]['fighter_image_name'],
	        				'gender' => $fightResult['fight']['fighters'][1]['gender'],
	        				'height_ft' => $fightResult['fight']['fighters'][1]['height_ft'],
	        				'height_in' => $fightResult['fight']['fighters'][1]['height_in'],
	        				'weight_lbs' => $fightResult['fight']['fighters'][1]['weight_lbs'],
	        				'wins' => $fightResult['fight']['fighters'][1]['wins'],
	        				'loses' => $fightResult['fight']['fighters'][1]['loses'],
	        				'draws' => $fightResult['fight']['fighters'][1]['draws'],
	        				'pivot' => [
	        					'fight_id' => $fightResult['fight']['fighters'][1]['pivot']['fight_id'],
	        					'fighter_id' => $fightResult['fight']['fighters'][1]['pivot']['fighter_id'],
	        					'odds' => $fightResult['fight']['fighters'][1]['pivot']['odds']
	        				],
	        				'powerupsAchieved' => $fightResult['fight']['fighters'][1]['powerupsAchieved']
	        			]
	        		],
	        	],
	        	'power_ups' => $fightResult['power_ups'],
	        ],
	        'totalTime' => date('i:s', strtotime($fightResult['total_time'])),
	        'roundTime' => date('i:s', strtotime($fightResult['total_time'].' -'.$timeAdjustment.'minute')),
	    ];
    }
}