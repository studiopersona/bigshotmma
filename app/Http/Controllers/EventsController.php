<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;
use Bsmma\Event;
use Bsmma\Promoter;
use Bsmma\divStrong\Transformers\EventTransformer as EventTransformer;

class EventsController extends ApiController
{
    protected $eventTransformer;

    public function __construct(Event $event, EventTransformer $eventTransformer)
    {
        $this->event = $event;
        $this->eventTransformer = $eventTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = $this->event
                    ->with('promoter')
                    ->where('date_time', '>', date('Y-m-d H:i:s', strtotime('-1 week')))
                    ->orderBy('date_time', 'asc')
                    ->get();

        return $this->respond([
            'events' => $this->eventTransformer->transformCollection($events->toArray()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = $this->event
                    ->where('id', $id)
                    ->with(['promoter'])
                    ->get();

        if ( $event->isEmpty() )
        {
            return $this->respondNotFound('Event does not exsist');
        }

        return $this->respond([
            'event' => $this->eventTransformer->transformCollection($event->toArray()),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
