<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    protected $dates = [];

    // Relationships ---------------------------
    public function promoter()
    {
        return $this->belongsTo('Bsmma\Promoter');
    }

    public function fights()
    {
        return $this->hasMany('Bsmma\Fight');
    }

    public function contests()
    {
        return $this->hasMany('Bsmma\Contests');
    }

    public function promotions()
    {
        return $this->hasMany('Bsmma\Promotion');
    }

    public function fightResults()
    {
        return $this->hasManyThrough('Bsmma\FightResult', 'Bsmma\Fight');
    }
}
