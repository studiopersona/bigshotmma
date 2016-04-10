<?php namespace Bsmma\divStrong\Transformers;

class FightResultTransformer extends Transformer {

	public function transform($fightResult)
    {
        return [
            'fightResults' => $fightResult,
        ];
    }
}