<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $guarded = [];

    protected $dates = [];

    public function event()
    {
        return $this->belongsTo('Bsmma\Event');
    }
}
