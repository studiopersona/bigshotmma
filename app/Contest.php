<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $guarded = [];

    protected $dates = [];


    // Relationships ---------------------------------------
    public function event()
    {
        return $this->belongsTo('Bsmma\Event');
    }

    public function contestType()
    {
        return $this->belongsTo('Bsmma\ContestType');
    }

    public function picks()
    {
        return $this->hasMany('Bsmma\Pick');
    }

    public function winners()
    {
        return $this->hasMany('Bsmma\Winner');
    }

    public function contestParticipants()
    {
        return $this->hasMany('Bsmma\ContestParticipant');
    }

    public function users()
    {
        return $this->belongsToMany('Bsmma\User', 'contest_participants');
    }

    public function fightResult()
    {
        return $this->belongsTo('Bsmma\FightResult');
    }

    public function userBalances()
    {
        return $this->belongsToMany('Bsmma\UserBalance');
    }

    public function contestParticipantsArchive()
    {
        return $this->hasMany('Bsmma\ContestParticipantsArchive');
    }
}
