<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $user);

    public function searchByMail(string $email);

    public function getPasswordHash(string $password);

    public function generateApiToken(User $user);
}
