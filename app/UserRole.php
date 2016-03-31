<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
	protected $gaurded = ['id','user_role'];

	public function users()
	{
		return $this->hasMany('Bsmma\User');
	}
}
