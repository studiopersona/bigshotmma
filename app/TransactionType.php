<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    protected $fillable = [];

    public function userBalances()
    {
    	return $this->hasMany('Bsmma/UserBalance');
    }
}
