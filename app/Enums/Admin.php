<?php

/**
 * Yöneticilerin durumları ile ilgili durumları
 * tanımlamak için kullanılacak ENUM yapısı
 */

namespace App\Enums;

enum Admin: int
{
    case ACTIVE = 1;
    case PASSIVE = 2;

    public static function title($title): string
    {
        return match ($title) {
            self::ACTIVE => __('ACTIVE'),
            self::PASSIVE => __('PASSIVE'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
