<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;
use Bsmma\PowerUp;
use Bsmma\divStrong\Transformers\PowerUpTransformer as PowerUpTransformer;

class PowerUpsController extends Controller
{
    public function __construct(PowerUp $powerUp, PowerUpTransformer $powerUpTransformer)
    {
        $this->powerUp = $powerUp;
        $this->powerUpTransformer = $powerUpTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->powerUpTransformer->transformCollection($this->powerUp->get()->toArray());
    }
}
