<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class StripeDetail extends Model
{
    protected $gaurded = [];

    public function user()
    {
    	return $this->belongsTo('Bsmma\User');
    }

    public function creditCardType()
    {
    	return $this->belongsTo('Bsmma\CreditCardType');
    }
}
