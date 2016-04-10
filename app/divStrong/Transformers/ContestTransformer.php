<?php namespace Bsmma\divStrong\Transformers;

class ContestTransformer extends Transformer {

	public function transform($contest)
    {
        return [
            'contest_id' => $contest['id'],
            'contest_type_name' => $contest['contest_type']['contest_type_name'],
            'buy_in' => $contest['entry_fee'],
            'max_participants' => $contest['max_participants'],
            'total_participants' => count($contest['users']),
            'picks_deadline' => date('m/d/Y h:ia', strtotime($contest['deadline'])),
            'description' => $contest['description'],
            'event_name' => $contest['event']['event_name'],
            'event_short_name' => $contest['event']['event_short_name'],
            'event_date' => date('m/d/Y', strtotime($contest['event']['date_time'])),
            'event_time' => date('h:ia', strtotime($contest['event']['date_time'])),
            'event_image_file' => $contest['event']['event_image_name'],
            'fights' => $contest['event']['fights'],
        ];
    }
}