<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Sử dụng User model vì bảng là "users"
use Spatie\Permission\Models\Role;
use App\Enums\RoleEnum;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('123456789'),
            'phone' => '0123456789',
        ]);
        $roleName = RoleEnum::getText(RoleEnum::ADMIN);
        $role = Role::where('name', $roleName)->where('guard_name', 'api')->first();

        if ($role) {
            // Gán role cho admin
            $admin->assignRole($role->name);
            // Gán tất cả permission theo role
            $admin->syncPermissions(RoleEnum::getPermissions(RoleEnum::ADMIN));
        } else {
            throw new \Exception("Role '$roleName' does not exist. Please run RoleSeeder first.");
        }
    }
}
