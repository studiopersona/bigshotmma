<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
	protected $table = "nationalities";

    protected $guarded = [];

    protected $dates = [];

    // Relationships -------------------------------
    public function fighters()
    {
        return $this->hasMany('Bsmma\Fighter');
    }
}
