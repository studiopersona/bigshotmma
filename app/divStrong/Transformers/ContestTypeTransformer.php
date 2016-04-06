<?php namespace Bsmma\divStrong\Transformers;

class ContestTypeTransformer extends Transformer {

	public function transform($contestType)
    {
        return [
            'id' => $contestType['id'],
            'contest_type_name' => $contestType['contest_type_name'],
            'contest_type_rules' => $contestType['contest_type_rules'],
            'image_name' => $contestType['image_name'],
        ];
    }
}