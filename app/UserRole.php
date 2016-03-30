<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
	protected $gaurded = ['id','user_role'];

	public function user()
	{
		return $this->hasMany('Bsmma\User');
	}
}
