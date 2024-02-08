<?php

/**
 * Sistemde kullanılan yapıları aktif ve pasif olarak
 * tanımlamak için kullanılacak ENUM yapısı
 */

namespace App\Enums;

enum Period: int
{
    case Day = 1;
    case Week = 2;
    case Month = 3;
    case Year = 4;

    public static function title($title): string
    {
        return match ($title) {
            self::Day => __('global.period_Day'),
            self::Week => __('global.period_Week'),
            self::Month => __('global.period_Month'),
            self::Year => __('global.period_Year'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
