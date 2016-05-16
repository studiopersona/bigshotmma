<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class UserBalance extends Model
{
	protected $gaurded = [];

    public function user()
    {
    	return $this->belongsTo('Bsmma\User');
    }

    public function transactionType()
    {
    	return $this->belongsTo('Bsmma\TransactionType');
    }
}
