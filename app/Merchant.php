<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $fillable = [];

    public function merchantTransactions()
    {
    	return $this->hasMany('Bsmma\MerchantTransaction');
    }
}
