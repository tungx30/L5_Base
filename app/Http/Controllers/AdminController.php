<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Repositories\Contracts\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected $repository;
    public function __construct(AdminRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $check = Auth::guard('admin')->attempt([
            'email'         => $request->email,
            'password'      => $request->password
        ]);
        if ($check) {
            $admin = Auth::guard('admin')->user();
            return response()->json([
                'status'    => true,
                'message'   => 'Login success',
                'token'     => $admin->createToken('token_admin')->plainTextToken,
            ]);
        } else {
            return response()->json([
                'status'    => false,
                'message'   => 'Check your password or email again',
            ]);
        }
    }

    /**
     * Handle admin logout.
     */
    public function logout()
    {
        Auth::guard('sanctum')->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Get authenticated admin profile.
     */
    public function profile()
    {
        return response()->json(Auth::guard('sanctum')->user());
    }
}
