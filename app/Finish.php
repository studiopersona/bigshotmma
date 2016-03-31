<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class Finish extends Model
{
    protected $guarded = [];

    protected $dates = [];

    // Relationships ------------------------
    public function fightResults()
    {
        return $this->hasMany('Bsmma\FightResult');
    }

    public function picks()
    {
        return $this->hasMany('Bsmma\Pick');
    }
}
