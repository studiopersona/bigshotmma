<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class MerchantTransaction extends Model
{
    protected $gaurded = [];

    public function merchant()
    {
    	return $this->belongsTo('Bsmma\Merchant');
    }

    public function userBalance()
    {
    	return $this->belongsTo('Bsmma\UserBalance');
    }
}
