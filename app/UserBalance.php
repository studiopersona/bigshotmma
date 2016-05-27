<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class UserBalance extends Model
{
	protected $gaurded = [];

    protected $fillable = ['user_id', 'amount', 'transaction_type_id'];

    public function user()
    {
    	return $this->belongsTo('Bsmma\User');
    }

    public function transactionType()
    {
    	return $this->belongsTo('Bsmma\TransactionType');
    }

    public function contests()
    {
    	return $this->belongsToMany('Bsmma\Contest');
    }
}
