<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class ContestType extends Model
{
    protected $guarded = [];

    protected $dates = [];

    // Relationships ---------------------------
    public function contests()
    {
        return $this->hasMany('Bsmma\Contest');
    }
}
