<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Criteria\RequestCriteria;

class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    public function model()
    {
        return User::class;
    }
    protected $fieldSearchable  = [
        'id' => 'id',
        'name'=>'like',
        'email'=>'like',
        'phone'=>'like',
    ];

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getAll($filters = [])
    {
        return $this->scopeQuery(function ($query) use ($filters) {
            if (!empty($filters['search']) && !empty($filters['searchFields'])) {
                $search = $filters['search'];
                $searchFields = explode(',', $filters['searchFields']);

                $query->where(function ($q) use ($search, $searchFields) {
                    foreach ($searchFields as $field) {
                        if (strpos($field, ':like') !== false) {
                            $q->orWhere(str_replace(':like', '', $field), 'LIKE', '%' . $search . '%');
                        } else {
                            $q->orWhere($field, '=', $search);
                        }
                    }
                });
            }

            return $query;
        })->paginate();
    }

    public function findById($id, $relations = [])
    {
        return $this->with($relations)->findOrFail($id);
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->create($data);
    }

    public function updateUser($id, array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->update($data, $id);
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
