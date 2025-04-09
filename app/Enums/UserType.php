<?php

namespace App\Enums;

enum UserType: string
{
    case PLATFORM_ADMIN = 'platform_admin';
    case TENANT_OWNER = 'tenant_owner';
    case TENANT_STAFF = 'tenant_staff';

    public function isAdmin(): bool
    {
        return $this === self::PLATFORM_ADMIN;
    }

    public function isTenantUser(): bool
    {
        return in_array($this, [self::TENANT_OWNER, self::TENANT_STAFF]);
    }

    public function isTenantOwner(): bool
    {
        return $this === self::TENANT_OWNER;
    }

    public function isTenantStaff(): bool
    {
        return $this === self::TENANT_STAFF;
    }

    public function getLabel(): string
    {
        return match($this) {
            self::PLATFORM_ADMIN => 'Yönetici',
            self::TENANT_OWNER => 'Sahip',
            self::TENANT_STAFF => 'Kullanıcı',
        };
    }
}
