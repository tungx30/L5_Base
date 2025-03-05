<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    public function model()
    {
        return User::class;
    }

    /**
     * Get all users with optional filters.
     */
    public function getAll($filters = [])
    {
        $query = $this->model->newQuery();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('phone', 'like', '%' . $filters['search'] . '%');
            });
        }
        if (!empty($filters['order_by'])) {
            $query->orderBy($filters['order_by'], $filters['sort'] ?? 'asc');
        }

        return $query->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Get user by ID with optional relationships.
     */
    public function findById($id, $relations = [])
    {
        $query = $this->model->newQuery();

        if (!empty($relations)) {
            $query->with($relations);
        }

        $user = $query->find($id);

        if (!$user) {
            throw new ModelNotFoundException("User not found");
        }

        return $user;
    }

    /**
     * Create a new user.
     */
    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->create($data);
    }

    /**
     * Update user by ID.
     */
    public function updateUser($id, array $data)
    {
        $user = $this->findById($id);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->update($data, $id);
    }

    /**
     * Delete user by ID.
     */
    public function deleteUser($id)
    {
        $user = $this->findById($id);
        return $this->delete($id);
    }

    /**
     * Handle user registration.
     */
    public function register(array $data)
    {
        return $this->createUser($data);
    }
}
