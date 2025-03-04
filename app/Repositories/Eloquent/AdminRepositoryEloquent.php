<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\AdminRepository;
use Prettus\Repository\Eloquent\BaseRepository;

class AdminRepositoryEloquent extends BaseRepository implements AdminRepository
{
    public function model()
    {
        return User::class;
    }

    public function findByToken(string $token)
    {
        return $this->model->where('remember_token', $token)->first();
    }
}
