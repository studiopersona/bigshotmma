<?php namespace Bsmma\divStrong\Transformers;

class FightResultTransformer extends Transformer {

	public function transform($fightResult)
    {
        return [
            'fightResults' => $fightResult,
            'totalTime' => date('i:s', strtotime($fightResult['total_time'])),
        ];
    }
}