<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class BogoPromo extends Model
{
    public function bogoPromosToUsers()
    {
    	return $this->hasMany('Bsmma\BogoPromosToUser');
    }
}
