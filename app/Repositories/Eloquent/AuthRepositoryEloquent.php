<?php

namespace App\Repositories\Eloquent;

use App\Models\Admin;
use App\Models\User;
use App\Repositories\Contracts\AuthRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthRepositoryEloquent extends BaseRepository implements AuthRepository
{
    public function model()
    {
        return Admin::class;
    }

    /**
     * Handle login for both Admin and User.
     */
    public function login(array $credentials)
    {
        $guards = ['admin', 'user'];
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->attempt($credentials)) {
                $user = Auth::guard($guard)->user();
                $tokenName = ($guard === 'admin') ? 'token_admin' : 'token_user';

                return [
                    'user' => $user,
                    'role' => $guard,
                    'token' => $user->createToken($tokenName)->plainTextToken,
                ];
            }
        }

        throw ValidationException::withMessages([
            'email' => ['Email or password is incorrect.'],
        ]);
    }

    /**
     * Handle logout.
     */
    public function logout()
    {
        $user = Auth::guard('sanctum')->user();
        if ($user) {
            $user->tokens()->delete();
        }
        return true;
    }

    /**
     * Get authenticated user's profile.
     */
    public function profile()
    {
        return Auth::guard('sanctum')->user();
    }
}
