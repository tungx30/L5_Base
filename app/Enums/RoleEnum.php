<?php

namespace App\Enums;

enum RoleEnum: int
{
    case ADMIN = 1;
    case USER = 2;

    public static function all(): array
    {
        return [
            self::ADMIN->value,
            self::USER->value,
        ];
    }

    public static function getText(RoleEnum $role): string
    {
        return match ($role) {
            self::ADMIN => 'admin',
            self::USER => 'user',
        };
    }
    public static function getPermissions(RoleEnum $role): array
    {
        return match ($role) {
            self::ADMIN => [
                PermissionEnum::USER_VIEW_PROFILE->value,
                PermissionEnum::USER_MANAGE->value,
                PermissionEnum::ADMIN_LOGOUT->value,
            ],
            self::USER => [
                PermissionEnum::USER_VIEW_PROFILE->value,
                PermissionEnum::USER_LOGOUT->value,
                PermissionEnum::USER_REGISTER->value,
            ],
        };
    }
}
