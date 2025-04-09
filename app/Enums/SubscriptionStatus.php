<?php

namespace App\Enums;

enum SubscriptionStatus: int
{
    case PENDING = 0;
    case ACTIVE = 1;
    case CANCELLED = 2;
    case EXPIRED = 3;

    public function getLabel(): string
    {
        return match($this) {
            self::PENDING => 'Beklemede',
            self::ACTIVE => 'Aktif',
            self::CANCELLED => 'İptal Edildi',
            self::EXPIRED => 'Süresi Doldu',
        };
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isCancelled(): bool
    {
        return $this === self::CANCELLED;
    }

    public function isExpired(): bool
    {
        return $this === self::EXPIRED;
    }
}
