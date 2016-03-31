<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class Fight extends Model
{
    protected $guarded = [];

    protected $dates = [];

    // Relationships ------------------------
    public function fighters()
    {
        return $this->belongsToMany('Bsmma\Fighter');
    }
}
