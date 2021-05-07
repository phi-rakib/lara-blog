<?php

namespace App\Http\Controllers;

use App\Repositories\Auth\AuthRepository;
use App\Repositories\Profile\ProfileRepositoryInterface;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private $authRepository;
    private $profileRepository;
    
    public function __construct(
        AuthRepository $authRepository,
        ProfileRepositoryInterface $profileRepository
        )
    {
        $this->authRepository = $authRepository;
        $this->profileRepository = $profileRepository;
    }

    public function store(Request $request)
    {
        $input = $request->only('body', 'website_url');
        return $this->profileRepository->create($input);
    }
}
