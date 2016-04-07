<?php namespace Bsmma\divStrong\Transformers;

class FightTransformer extends Transformer {

	public function transform($fight)
    {
        return [
            // 'data' => $fight,
            'event' => [
                'event_id' => $fight['event_id'],
                'event_short_name' => $fight['event']['event_short_name'],
            ],
            'fights' => $fight['event']['fights'],
        ];
    }
}