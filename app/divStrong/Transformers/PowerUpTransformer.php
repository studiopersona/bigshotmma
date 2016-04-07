<?php namespace Bsmma\divStrong\Transformers;

class PowerUpTransformer extends Transformer {

	public function transform($powerUp)
    {
        return [
            'power_up_id' => $powerUp['id'],
            'name' => $powerUp['power_up_name'],
            'description' => $powerUp['power_up_description'],
            'bonus_points' => $powerUp['bonus_points'],
            'penalty_points' => $powerUp['penalty_points'],
            'color' => $powerUp['power_up_color'],
            'image_name' => $powerUp['power_up_image_name'],
        ];
    }
}