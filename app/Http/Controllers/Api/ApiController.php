<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    //
    protected function respond($data, $statusCode = 200, $headers = [])
    {
        return response()->json($data, $statusCode, $headers);
    }

    protected function respondError($message, $statusCode)
    {
        return $this->respond([
            'errors' => [
                'message' => $message,
                'status_code' => $statusCode
            ]
        ], $statusCode);
    }

    protected function respondInternalError($message = 'Internal Error')
    {
        return $this->respondError($message, 500);
    }

    protected function respondNotFound($message = 'Not Found')
    {
        return $this->respondError($message, 404);
    }

    protected function respondBadRequest($message = 'BadRequest')
    {
        return $this->respondError($message, 400);
    }

    protected function respondSuccess()
    {
        return $this->respond(null);
    }

    protected function respondCreated($data)
    {
        return $this->respond($data, 201);
    }

    protected function respondNoContent()
    {
        return $this->respond(null, 204);
    }
}
