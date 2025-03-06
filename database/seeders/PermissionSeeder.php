<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Enums\PermissionEnum;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PermissionEnum::cases() as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission->value],
                ['guard_name' => 'api']
            );
        }
    }
}
