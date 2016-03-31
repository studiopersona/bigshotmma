<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $guarded = [];

    protected $dates = [];

    public function discountType()
    {
        return $this->belongsTo('Bsmma\DiscountType');
    }
}
