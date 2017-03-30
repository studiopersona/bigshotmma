<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class BogoPromosToUser extends Model
{
    public function bogoPromos()
    {
    	return $this->belongsTo('Bsmma\BogoPromo');
    }

    public function users()
    {
    	return $this->belongsTo('Bsmma\User');
    }
}
