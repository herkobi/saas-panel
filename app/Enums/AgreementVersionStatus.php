<?php

namespace App\Enums;

enum AgreementVersionStatus: int
{
    case DRAFT = 1;
    case PUBLISHED = 2;
    case ARCHIVED = 3;

    public static function title($title): string
    {
        return match ($title) {
            self::DRAFT => 'TASLAK',
            self::PUBLISHED => 'YAYINLANMIŞ',
            self::ARCHIVED => 'ARŞİVLENMİŞ',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'text-bg-secondary',
            self::PUBLISHED => 'text-bg-success',
            self::ARCHIVED => 'text-bg-warning',
        };
    }

    /**
     * Başlığa üstteki yapının dışında erişilmesini sağlar
     */
    public static function getTitle($type)
    {
        switch ($type) {
            case self::DRAFT->value:
                return 'TASLAK';
            case self::PUBLISHED->value:
                return 'YAYINLANMIŞ';
            case self::ARCHIVED->value:
                return 'ARŞİVLENMİŞ';
            default:
                throw new \Exception('Invalid type');
        }
    }
}
