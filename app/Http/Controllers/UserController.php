<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponseTrait;

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
        return $this->successResponse(
            $this->userRepository->getAll($request->all()),
            'List Of Users'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        return $this->successResponse(
            $this->userRepository->createUser($request->validated()),
            'User Created Successfully',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return $this->successResponse(
            $this->userRepository->findByIdUser($id),
            'User Information'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        return $this->successResponse(
            $this->userRepository->updateUser($id, $request->validated()),
            'User Updated Successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->userRepository->deleteUser($id);
        return $this->successResponse([], 'User Deleted Successfully', Response::HTTP_OK);
    }

    /**
     * Handle user registration.
     */
    public function register(CreateUserRequest $request)
    {
        return $this->successResponse(
            $this->userRepository->register($request->validated()),
            'User Registered Successfully',
            Response::HTTP_CREATED
        );
    }
}
