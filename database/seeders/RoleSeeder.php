<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Enums\RoleEnum;
use App\Enums\PermissionEnum;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (RoleEnum::all() as $roleValue) {
            $roleName = RoleEnum::getText(RoleEnum::from($roleValue));
            $role = Role::updateOrCreate(
                ['name' => $roleName],
                ['guard_name' => 'api']
            );
            $role->syncPermissions(RoleEnum::getPermissions(RoleEnum::from($roleValue)));
        }
    }
}

