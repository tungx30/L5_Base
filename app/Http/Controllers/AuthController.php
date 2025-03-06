<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\Contracts\AuthRepository;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected $repository;

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle login for both Admin and User.
     */
    public function login(LoginRequest $request)
    {
        $result = $this->repository->login($request->all());
        return $this->successResponse($result, 'Login successful');
    }

    /**
     * Handle logout.
     */
    public function logout()
    {
        $this->repository->logout();
        return $this->successResponse([], 'Logged out successfully');
    }

    /**
     * Get authenticated user's profile.
     */
    public function profile()
    {
        $user = $this->repository->profile();
        return $this->successResponse($user, 'User profile retrieved successfully');
    }
}
