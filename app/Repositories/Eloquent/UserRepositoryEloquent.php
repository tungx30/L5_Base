<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Criteria\RequestCriteria;
use Spatie\Permission\Models\Role;
use App\Enums\RoleEnum;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    public function model()
    {
        return User::class;
    }

    protected $fieldSearchable  = [
        'id' => 'id',
        'name' => 'like',
        'email' => 'like',
        'phone' => 'like',
        'order.id' => '='
    ];

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getAll($filters = [])
    {
        return $this->paginate(10);
    }

    public function findByIdUser($id)
    {
        return $this->findOrFail($id);
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->create($data);
        $roleName = RoleEnum::getText(RoleEnum::USER);
        $user->syncRoles($roleName);
        return $user;
    }

    public function updateUser($id, array $data)
    {
        $user = $this->find($id);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);
        if (!empty($data['role'])) {
            $role = RoleEnum::getText($data['role']);
            if ($user->hasRole($role) === false) {
                $user->syncRoles([$role]);
            }
        }

        return $user;
    }

    public function deleteUser($id)
    {
        return $this->delete($id);
    }

    public function register(array $data)
    {
        return $this->createUser($data);
    }
}
