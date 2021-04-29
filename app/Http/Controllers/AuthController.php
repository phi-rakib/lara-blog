<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $request->validated();

        $input = $request->only(['email', 'password']);

        if (!Auth::attempt($input)) {
            return response()
                ->json(
                    [
                        'message' => 'Invalid login details',
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return (new AuthResource($user))
            ->additional(["token" => $token]);
    }

    public function registration(RegistrationRequest $request)
    {
        $request->validated();

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $token = $user->createToken('MyAuthApp')->plainTextToken;

        return (new AuthResource($user))
            ->additional(["token" => $token]);
    }
}
