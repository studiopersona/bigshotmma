<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class PaypalEmail extends Model
{
    protected $guarded = [];

    public function user()
    {
    	return $this->belongsTo('Bsmma\User');
    }
}
