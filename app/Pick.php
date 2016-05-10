<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class Pick extends Model
{
    protected $guarded = [];

    protected $dates = [];

    public function powerUp()
    {
        return $this->belongsTo('Bsmma\PowerUp');
    }

    public function contest()
    {
        return $this->belongsTo('Bsmma\Contest');
    }

    public function fightResult()
    {
        return $this->hasOne('Bsmma\FightResult', 'fight_id', 'fight_id');
    }

    public function fight()
    {
        return $this->belongsTo('Bsmma\Fight');
    }

    public function fighter()
    {
        return $this->belongsTo('Bsmma\Fighter', 'winning_fighter_id');
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
