<?php

namespace App\Enums;

enum ContractType: string
{
    case GENERAL = 'Genel';
    case MEMBERSHIP = 'Üyelik';
    case PAYMENT = 'Ödeme';

    // Tüm türleri döndüren bir metod
    public static function values(): array
    {
        return array_map(fn(self $type) => $type->value, self::cases());
    }
}
