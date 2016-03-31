<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class Promoter extends Model
{
    protected $guarded = [];

    protected $dates = [];

    public function events()
    {
        return $this->hasMany('Bsmma\Event');
    }
}
