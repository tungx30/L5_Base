<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\AuthRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Permission\Models\Role;
use App\Enums\RoleEnum;

class AuthRepositoryEloquent extends BaseRepository implements AuthRepository
{
    public function model()
    {
        return User::class;
    }

    /**
     * Handle login and return role & permissions.
     */
    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email hoặc mật khẩu không đúng.'],
            ]);
        }

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                'role' =>  $user->roles,
                'token'     => $user->createToken('token_admin')->plainTextToken,
            ]
        ], 200);
    }


    /**
     * Handle logout.
     */
    public function logout()
    {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
        }
        return response()->json(['message' => 'Đăng xuất thành công.'], 200);
    }

    /**
     * Get authenticated user's profile with role and permissions.
     */
    public function profile()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
        return response()->json([
            'user' => $user,
            'role' =>  $user->roles,
        ]);
    }
}
