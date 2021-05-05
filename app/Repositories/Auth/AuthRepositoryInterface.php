<?php

namespace App\Repositories\Auth;

use App\Models\User;

interface AuthRepositoryInterface {
  public function getAuthUser() : User;
  public function getAuthId() : int;
  public function authAttempt($user) : bool;
}