<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthResource;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiController
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(AuthRequest $request)
    {
        $input = $request->validated();

        if (!Auth::attempt($input)) {
            return $this->respondUnauthorized(['message' => 'Invalid login details']);
        }

        $user = $this->userRepository->searchByMail($request['email']);

        $token = $this->userRepository->generateApiToken($user);

        return (new AuthResource($user))
            ->additional(["token" => $token]);
    }

    public function registration(AuthRequest $request)
    {
        $input = $request->validated();

        $input['password'] = $this->userRepository->getPasswordHash($input['password']);

        $user = $this->userRepository->create($input);

        $token = $this->userRepository->generateApiToken($user);

        return (new AuthResource($user))
            ->additional(["token" => $token]);
    }
}
