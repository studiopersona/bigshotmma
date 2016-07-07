<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;

use Bsmma\Event;
use Bsmma\PowerUp;

class LanderController extends Controller
{
	public function __construct(Event $event, PowerUp $powerUp)
	{
		$this->event = $event;
		$this->powerUp = $powerUp;
	}

    public function index()
    {
    	$event = $this->event->with([
    					'contests',
    					'contests.contestType',
    					'contests.contestParticipant'
    				])
    				->where('date_time', '>=', date('Y-m-d'))
    				->orderBy('date_time', 'asc')
    				->first();

    	return response()->view('templates.lander', compact('event'));
    }
}
