<?php namespace Bsmma\divStrong\Transformers;

class ProfileTransformer extends Transformer {

	public function transform($user)
    {
        dump($user);
        return [
            'name' => $user['player_name'],
            'email' => $user['email'],
            'avatar' => $user['avatar'],
        ];
    }
}