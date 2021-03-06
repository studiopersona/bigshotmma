<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class Fight extends Model
{
    protected $guarded = [];

    protected $dates = [];

    // Relationships ------------------------
    public function fighters()
    {
        return $this->belongsToMany('Bsmma\Fighter')->withPivot('odds');
    }

    public function picks()
    {
    	return $this->hasMany('Bsmma\Pick');
    }

    public function event()
    {
        return $this->belongsTo('Bsmma\Event');
    }

    public function fightResults()
    {
        return $this->hasMany('Bsmma\FightResult');
    }
}
