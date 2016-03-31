<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class WeightClass extends Model
{
    protected $guarded = [];

    protected $dates = [];

    public function fighters()
    {
        return $this->hasMany('Bsmma\Fighter');
    }
}
