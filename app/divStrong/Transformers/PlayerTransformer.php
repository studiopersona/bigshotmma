<?php namespace Bsmma\divStrong\Transformers;

class PlayerTransformer extends Transformer {

	public function transform($player)
    {
        return [
            //'data' => $player,
            'contest' => [
                'event_id' => $player['event']['id'],
                'event_short_name' => $player['event']['event_short_name'],
                'event_date' => date('F j, Y', strtotime($player['event']['date_time'])),
                'event_image' => $player['event']['event_image_name'],
                'max_participants' => $player['max_participants'],
                'total_participants' => count($player['users']),
                'buy_in' => $player['entry_fee'],
                'prize_pool' => count($player['users']) * (int)$player['entry_fee'],
                'contest_type_name' => $player['contest_type']['contest_type_name'],
                'contest_type_id' => $player['contest_type']['id'],
                'contest_id' => $player['id'],
                'entry_deadline' => date('Y-m-d', strtotime($player['event']['date_time'].' -30 minute')).'T'.date('G:i:s', strtotime($player['event']['date_time'].' -30 minute')).'-0400',
            ],
            'participants' => $player['users'],
        ];
    }
}