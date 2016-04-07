<?php namespace Bsmma\divStrong\Transformers;

class FinishTransformer extends Transformer {

	public function transform($finish)
    {
        return [
            'id' => $finish['id'],
            'name' => $finish['finish_name'],
            'points' => $finish['points']
        ];
    }
}