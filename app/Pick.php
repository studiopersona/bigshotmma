<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class Pick extends Model
{
    protected $guarded = [];

    protected $dates = [];

    public function powerUps()
    {
        return $this->belongsToMany('Bsmma\PowerUp');
    }

    public function fightResult()
    {
        return $this->belongsTo('Bsmma\FightResult');
    }

    public function fighter()
    {
        return $this->belongsTo('Bsmma\Fighter');
    }

    public function finish()
    {
        return $this->belongsTo('Bsmma\Finish');
    }

    public function user()
    {
        return $this->belongsTo('Bsmma\User');
    }
}
