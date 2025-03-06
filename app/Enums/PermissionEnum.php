<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case USER_VIEW_PROFILE = 'user-view-profile';
    case USER_LOGOUT = 'user-logout';
    case ADMIN_LOGOUT = 'admin-logout';
    case USER_MANAGE = 'user-manage';
    case USER_REGISTER = 'user-register';
}
