<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class ContestParticipant extends Model
{
    protected $guarded = [];

    protected $dates = [];

    // Relationships -------------------------------------
    public function contest()
    {
        return $this->belongsTo('Bsmma\Contest');
    }

    public function user()
    {
        return $this->belongsTo('Bsmma\User');
    }
}
