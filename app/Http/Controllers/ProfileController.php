<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Repositories\Profile\ProfileRepositoryInterface;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
        $this->middleware('auth:sanctum')->except(['show']);
    }

    public function store(ProfileRequest $request)
    {
        $input = $request->only('body', 'website_url');
        return $this->profileRepository->create($input);
    }

    public function show($id)
    {
        return $this->profileRepository->get($id);
    }

}
