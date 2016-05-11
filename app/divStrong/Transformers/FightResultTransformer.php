<?php namespace Bsmma\divStrong\Transformers;

class FightResultTransformer extends Transformer {

	public function transform($fightResult)
    {
    	$timeAdjustment = ((int)$fightResult['round'] - 1) * 5;

        return [
            'fightResults' => $fightResult,
            'totalTime' => date('i:s', strtotime($fightResult['total_time'])),
            'roundTime' => date('i:s', strtotime($fightResult['total_time'].' -'.$timeAdjustment.'minute'))
        ];
    }
}