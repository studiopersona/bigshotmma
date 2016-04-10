<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;
use Bsmma\User;
use Bsmma\Pick;
use Bsmma\divStrong\Transformers\PickTransformer;
use Bsmma\divStrong\Transformers\PlayerPickTransformer;

class PicksController extends ApiController
{
    public function __construct(
        Pick $pick,
        User $user,
        PickTransformer $pickTransformer,
        PlayerPickTransformer $playerPickTransformer
    )
    {
        $this->pick = $pick;
        $this->user = $user;
        $this->pickTransformer = $pickTransformer;
        $this->playerPickTransformer = $playerPickTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $picks = $this->pick->with([
                    'contest.event.fights.fighters',
                    'fight',
                    'finish',
                    'fighter',
                    'powerUp',
                ])
                ->where('user_id', $this->user->getAuthUser())
                ->get();

        return $this->respond([
            'picks' => $this->pickTransformer->transformCollection($picks->toArray()),
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
        $user = \JWTAuth::parseToken()->authenticate();
        $requestData = $request->all();

        $data = [];
        $data['user_id'] = $user->id;

        foreach ( $requestData['picks'] as $pickRow )
        {
            foreach ( $pickRow as $column => $value )
            {
                if ( (int)$value === 0 ) $value = NULL;
                $data[$column] = $value;
            }

            $this->pick->insert($data);
        }

        return $this->respond([
            'success' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function showCard($contest_id)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $picks = $this->pick->with([
                        'contest',
                        'contest.event',
                        'contest.users',
                        'contest.event.fights.fighters',
                        'fight',
                        'fighter',
                        'finish',
                        'powerUp',
                    ])
                    ->where('contest_id', $contest_id)
                    ->where('user_id', $user->id)
                    ->get();

        if ( $picks->isEmpty() )
        {
            return $this->respondNotFound('No Picks Found');
        }

        return $this->respond([
            'picks' => $this->playerPickTransformer->transformCollection($picks->toArray()),
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
