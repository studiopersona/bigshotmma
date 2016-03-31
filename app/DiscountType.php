<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class DiscountType extends Model
{
    protected $guarded = [];

    protected $dates = [];

    public function discoutTypes()
    {
        return $this->hasMany('Bsmma\DiscountType');
    }
}
