<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class ContestUserBalance extends Model
{
	protected $table    = 'contest_user_balance';
	protected $fillable = ['contest_id', 'user_balance_id', 'is_entry'];
	public $timestamps  = false;
}
