<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    protected $guarded = [];

    protected $dates = [];

    public function user()
    {
        return $this->belongsTo('Bsmma\User');
    }

    public function fighter()
    {
        return $this->belongsToMany('Bsmma\Fighter');
    }
}
