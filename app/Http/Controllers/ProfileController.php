<?php

namespace App\Http\Controllers;

use App\Repositories\Profile\ProfileRepositoryInterface;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function store(Request $request)
    {
        $input = $request->only('body', 'website_url');
        return $this->profileRepository->create($input);
    }
}
