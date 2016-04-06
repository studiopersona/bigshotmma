<?php namespace Bsmma\divStrong\Transformers;

class PlayerTransformer extends Transformer {

	public function transform($player)
    {
        return [
            //'data' => $player,
            'contest' => [
                'event_short_name' => $player['event']['event_short_name'],
                'event_date' => date('F j, Y', strtotime($player['event']['date_time'])),
                'max_participants' => $player['max_participants'],
                'total_participants' => count($player['users']),
                'buy_in' => $player['entry_fee'],
                'prize_pool' => count($player['users']) * (int)$player['entry_fee'],
                'contest_type_name' => $player['contest_type']['contest_type_name'],
            ],
            'participants' => $player['users'],
        ];
    }
}