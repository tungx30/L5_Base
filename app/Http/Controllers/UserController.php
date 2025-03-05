<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ApiResponseTrait;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->getAll($request->all());
        return $this->successResponse($users, 'List Of Users');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $user = $this->userRepository->createUser($request->all());
        return $this->successResponse($user, 'User Created Successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user = $this->userRepository->findById($id);
            return $this->successResponse($user, 'User Information');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('User Does Not Exist ', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = $this->userRepository->updateUser($id, $request->all());
            return $this->successResponse($user, 'User Updated Successful');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('User Does Not Exist', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->userRepository->deleteUser($id);
            return $this->successResponse([], 'User Deleted Successfully', Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('User Does Not Exist', Response::HTTP_NOT_FOUND);
        }
    }
           /**
     * Handle user register.
     */
    public function register(CreateUserRequest $request)
    {
        $user = $this->userRepository->createUser($request->all());
        return $this->successResponse($user, 'User Registed Successfully', Response::HTTP_CREATED);
    }
        /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $check = Auth::guard('user')->attempt([
            'email'         => $request->email,
            'password'      => $request->password
        ]);
        if ($check) {
            $admin = Auth::guard('user')->user();
            return response()->json([
                'status'    => true,
                'message'   => 'Login success',
                'token'     => $admin->createToken('token_user')->plainTextToken,
            ]);
        } else {
            return response()->json([
                'status'    => false,
                'message'   => 'Check your password or email again',
            ]);
        }
    }

    /**
     * Handle user logout.
     */
    public function logout()
    {
        Auth::guard('sanctum')->user()->tokens()->delete();
        return $this->successResponse('Logout Success');
    }

    /**
     * Get authenticated user profile.
     */
    public function profile()
    {
        $user = Auth::guard('sanctum')->user();
        return $this->successResponse($user,'This is your profile');
    }
}
