<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;

use Bsmma\Http\Requests;

class ApiController extends Controller
{
    private $statusCode = 200;

    public function getStatusCode()
    {
    	return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
    	$this->statusCode = $statusCode;
    }

    public function respondInvaildCredentials($message = 'Not Authorized')
    {
        $statusCode = $this->setStatusCode(401);
        return $this->respondWithError($message);
    }

    public function respondNotFound($message = 'Not Found')
    {
    	$statusCode = $this->setStatusCode(404);
    	return $this->respondWithError($message);
    }

    public function respond($data, $headers = [])
    {
    	return response()->json($data, $this->getStatusCode(), $headers);
    }

    public function respondWithError($message)
    {
    	return $this->respond([
    		'error' => [
    			'message' => $message,
    			'status_code' => $this->getStatusCode(),
    		],
    	]);
    }
}
