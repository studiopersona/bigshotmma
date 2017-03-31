<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class BogoPromosToUser extends Model
{
	protected $guarded = [];

    public function bogoPromo()
    {
    	return $this->belongsTo('Bsmma\BogoPromo');
    }

    public function user()
    {
    	return $this->belongsTo('Bsmma\User');
    }
}
