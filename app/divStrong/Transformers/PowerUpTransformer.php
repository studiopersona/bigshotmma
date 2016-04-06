<?php namespace Bsmma\divStrong\Transformers;

class PowerUpTransformer extends Transformer {

	public function transform($powerUp)
    {
        return [
            'data' => $powerUp,
        ];
    }
}