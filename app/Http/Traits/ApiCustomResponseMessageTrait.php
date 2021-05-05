<?php

namespace App\Http\Traits;

trait ApiCustomResponseMessageTrait
{
    protected function messageCreated($modelName)
    {
        return ["message" => "$modelName Created successfully"];
    }

    protected function messageUpdated($modelName)
    {
        return ["message" => "$modelName Updated successfully"];
    }
}
