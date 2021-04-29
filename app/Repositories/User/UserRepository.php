<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $user)
    {
        return User::create($user);
    }

    public function searchByMail(string $email)
    {
        return User::where('email', $email)->firstOrFail();
    }

    public function getPasswordHash(string $password)
    {
        return bcrypt($password);
    }

    public function generateApiToken(User $user)
    {
        return $user->createToken('MyAuthApp')->plainTextToken;
    }
}
