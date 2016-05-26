<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class CreditCardType extends Model
{
    protected $gaurded = [];

    public function stripeDetails()
    {
    	return $this->hasMany('Bsmma\StripeDetail');
    }
}
