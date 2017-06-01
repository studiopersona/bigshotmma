<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class BogoPromo extends Model
{
	protected $guarded = [];

    public function bogoPromosToUsers()
    {
    	return $this->hasMany('Bsmma\BogoPromosToUser');
    	// this is a test
    }
}
