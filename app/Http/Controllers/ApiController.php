<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    protected function respond($data = [], $status = 200, array $headers = [])
    {
        return response()->json($data, $status, $headers);
    }

    protected function respondNoContent()
    {
        return $this->respond("", Response::HTTP_NO_CONTENT);
    }

    protected function respondUnauthorized($data)
    {
        return $this->respond($data, Response::HTTP_UNAUTHORIZED);
    }
}
