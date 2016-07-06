<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;

class LanderController extends Controller
{
    public function index()
    {
    	return response()->view('templates.lander');
    }
}
