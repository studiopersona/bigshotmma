<?php namespace Bsmma\divStrong\Transformers;

class ContestTransformer extends Transformer {

	public function transform($contest)
    {
        return [
            'contest_id' => $contest['id'],
            'contest_type_name' => $contest['contest_type']['contest_type_name'],
            'buy_in' => $contest['entry_fee'],
            'max_participants' => $contest['max_participants'],
            'picks_deadline' => date('m/d/Y h:ia', strtotime($contest['deadline'])),
            'description' => $contest['description'],
            'event_date' => date('m/d/Y h:ia', strtotime($contest['event']['date_time'])),
            'fights' => $contest['event']['fights'],
        ];
    }
}