<?php

/**
 * Hesapların durumları ile ilgili durumları
 * tanımlamak için kullanılacak ENUM yapısı
 */

namespace App\Enums;

enum Account: int
{
    case ACTIVE = 1;
    case PASSIVE = 2;
    case BANNED = 3;
    case DELETED = 4;

    public static function title($title): string
    {
        return match ($title) {
            self::ACTIVE => __('global.user.active'),
            self::PASSIVE => __('global.user.passive'),
            self::BANNED => __('global.user.banned'),
            self::DELETED => __('global.user.deleted'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
