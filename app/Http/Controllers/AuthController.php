<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthResource;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class AuthController extends ApiController
{
    protected $authRepository;
    protected $userRepository;

    public function __construct(
        AuthRepositoryInterface $authRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->authRepository = $authRepository;
        $this->userRepository = $userRepository;
    }

    public function login(AuthRequest $request)
    {
        $input = $request->validated();

        if (!$this->authRepository->authAttempt($input)) {
            return $this->respondUnauthorized(['message' => 'Invalid login details']);
        }

        $user = $this->userRepository->searchByMail($input['email']);

        $token = $this->authRepository->generateApiToken($user);

        return (new AuthResource($user))
            ->additional(["token" => $token]);
    }

    public function registration(AuthRequest $request)
    {
        $input = $request->validated();

        $input['password'] = $this->authRepository->getPasswordHash($input['password']);

        $user = $this->userRepository->create($input);

        $token = $this->authRepository->generateApiToken($user);

        return (new AuthResource($user))
            ->additional(["token" => $token]);
    }
}
