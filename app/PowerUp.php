<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class PowerUp extends Model
{
    protected $guarded = [];

    protected $dates = [];

    public function picks()
    {
        return $this->hasMany('Bsmma\Pick');
    }

    public function fightResults()
    {
    	return $this->belongsToMany('Bsmma\FightResult');
    }
}
