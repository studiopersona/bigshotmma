<?php namespace Bsmma\divStrong\Transformers;

class FightTransformer extends Transformer {

	public function transform($fight)
    {
        return [
            'data' => $fight,
        ];
    }
}