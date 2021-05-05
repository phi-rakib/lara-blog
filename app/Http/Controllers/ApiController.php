<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiCustomResponseTrait;

class ApiController extends Controller
{
    use ApiCustomResponseTrait;

    private $modelName;

    public function __construct($modelName)
    {
        $this->modelName = $modelName;
    }

    protected function messageCreated()
    {
        return ["message" => "$this->modelName Created successfully"];
    }

    protected function messageUpdated()
    {
        return ["message" => "$this->modelName Updated successfully"];
    } 
}
