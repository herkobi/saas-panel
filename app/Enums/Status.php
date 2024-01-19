<?php

/**
 * Sistemde kullanılan yapıları aktif ve pasif olarak
 * tanımlamak için kullanılacak ENUM yapısı
 */

namespace App\Enums;

enum Status: int
{
    case ACTIVE = 1;
    case PASSIVE = 2;

    public static function title($title): string
    {
        return match ($title) {
            self::ACTIVE => __('admin/global.active'),
            self::PASSIVE => __('admin/global.passive'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
