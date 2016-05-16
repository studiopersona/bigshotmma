<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class FightResult extends Model
{
    protected $guarded = [];

    protected $dates = [];

    // Relationships ------------------------
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

    public function powerUps()
    {
        return $this->belongsToMany('Bsmma\PowerUp')->withPivot('fighter_id');
    }

    public function contests()
    {
        return $this->hasMany('Bsmma\Contest');
    }

    public function pick()
    {
        return $this->hasOne('Bsmma\Pick', 'fight_id', 'fight_id');
    }
}
