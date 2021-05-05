<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiCustomResponseMessageTrait;
use App\Http\Traits\ApiCustomResponseTrait;

class ApiController extends Controller
{
    use ApiCustomResponseTrait, ApiCustomResponseMessageTrait;
}
