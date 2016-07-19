<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class MerchantTransaction extends Model
{
    protected $gaurded = [];
    protected $fillable = ['merchant_id', 'user_balances_id', 'transaction_id', 'payment_id'];

    public function merchant()
    {
    	return $this->belongsTo('Bsmma\Merchant');
    }

    public function userBalance()
    {
    	return $this->belongsTo('Bsmma\UserBalance');
    }
}
