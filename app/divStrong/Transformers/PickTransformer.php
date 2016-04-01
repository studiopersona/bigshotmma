<?php namespace Bsmma\divStrong\Transformers;

class PickTransformer extends Transformer {

	public function transform($pick)
    {
        return [
            'picks' => $pick,
        ];
    }
}