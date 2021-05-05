<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    private $modelName;

    public function __construct($modelName)
    {
        $this->modelName = $modelName;
    }

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

    protected function messageCreated()
    {
        return ["message" => "$this->modelName Created successfully"];
    }

    protected function messageUpdated()
    {
        return ["message" => "$this->modelName Updated successfully"];
    }

    protected function respondNotFound()
    {
        return $this->respond("Not Found", Response::HTTP_NOT_FOUND);
    }
}
