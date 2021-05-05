<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements AuthRepositoryInterface {

  public function getAuthUser() : User {
    return Auth::user();
  }

  public function getAuthId() : int {
    return Auth::id();
  }
}