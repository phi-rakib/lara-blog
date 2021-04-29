<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function create(array $user);

    public function searchByMail(string $email);
}
