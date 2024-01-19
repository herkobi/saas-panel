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
            self::ACTIVE => __('admin/global.admin.active'),
            self::PASSIVE => __('admin/global.admin.passive'),
            self::BANNED => __('admin/global.admin.banned'),
            self::DELETED => __('admin/global.admin.passive'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
