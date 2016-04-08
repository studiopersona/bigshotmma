<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class Fighter extends Model
{
    protected $guarded = [];

    protected $dates = [];

    // Relationships ------------------------
    public function fights()
    {
        return $this->belongsToMany('Bsmma\Fight')->withPivot('odds');
    }

    public function nationality()
    {
    	return $this->belongsTo('Bsmma\Nationality');
    }
}
