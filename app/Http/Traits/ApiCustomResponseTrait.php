<?php

namespace App\Http\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ApiCustomResponseTrait
{
    protected function respond($data = [], $status = Response::HTTP_OK, array $headers = [])
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

    protected function respondNotFound()
    {
        return $this->respond("Not Found", Response::HTTP_NOT_FOUND);
    }
}
