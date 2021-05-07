<?php

namespace App\Repositories\Profile;

use App\Repositories\Auth\AuthRepositoryInterface;

class ProfileRepository implements ProfileRepositoryInterface
{
    private $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function create($profile)
    {
        $user = $this->authRepository->getAuthUser();
        return $user->profile()->create($profile);
    }
}
