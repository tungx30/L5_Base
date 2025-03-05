<?php

namespace App\Repositories\Eloquent;

use App\Models\Admin;
use App\Repositories\Contracts\AdminRepository;
use Prettus\Repository\Eloquent\BaseRepository;

class AdminRepositoryEloquent extends BaseRepository implements AdminRepository
{
    public function model()
    {
        return Admin::class;
    }
}
