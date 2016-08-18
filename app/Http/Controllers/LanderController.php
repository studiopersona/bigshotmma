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
    					'contests.contestParticipants'
    				])
    				->where('date_time', '>=', date('Y-m-d'))
    				->orderBy('date_time', 'asc')
    				->first();

        $powerUps = $this->powerUp->get();

    	return response()->view('templates.lander', compact('event', 'powerUps'));
    }

    public function policy()
    {
        $powerUps = $this->powerUp->get();

        return response()->view('information.policy', compact('powerUps'));
    }

    public function terms()
    {
        $powerUps = $this->powerUp->get();

        return response()->view('information.terms', compact('powerUps'));
    }

    public function contact()
    {
        $powerUps = $this->powerUp->get();

        return response()->view('information.contact', compact('powerUps'));
    }
}
