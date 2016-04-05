<?php namespace Bsmma\divStrong\Transformers;

class EventTransformer extends Transformer {

	public function transform($event)
    {
        return [
            'event_id' => $event['id'],
            'event_name' => $event['event_name'],
            'venue' => $event['venue'],
            'city' => $event['city'],
            'state' => $event['state'],
            'country' => $event['country'],
            'event_image_file' => $event['event_image_name'],
            'promoter' => [
                'promoter_name' => $event['promoter']['promoter'],
                'promoter_slug' => $event['promoter']['slug'],
            ],
            'date' => date('m/d/Y', strtotime($event['date_time'])),
            'time' => date('g:ia', strtotime($event['date_time'])),
        ];
    }
}