<?php

namespace Bsmma;

use Illuminate\Database\Eloquent\Model;

class ContestParticipantsArchive extends Model
{
    protected $table = 'contest_participants_archive';

    public function user()
    {
    	return $this->belongsTo('Bsmma\User');
    }

    public function contest()
    {
    	return $this->belongsTo('Bsmma\Contest');
    }
}
