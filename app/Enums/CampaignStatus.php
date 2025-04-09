<?php

namespace App\Enums;

enum CampaignStatus: int
{
    case ACTIVE = 1;      // Aktif ilan
    case ARCHIVED = 2;    // Süresi dolmuş ilan
    case DRAFT = 3;       // Taslak olarak saklanan ilan

    public function title(): string
    {
        return match ($this) {
            self::ACTIVE => 'Aktif',
            self::ARCHIVED => 'Arşiv',
            self::DRAFT => 'Taslak',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'text-bg-success',       // Yeşil renk (aktif)
            self::ARCHIVED => 'text-bg-secondary',  // Gri renk (pasif/archived)
            self::DRAFT => 'text-bg-warning',      // Sarı renk (taslak)
        };
    }

    public static function fromValue(int $value): self
    {
        return match ($value) {
            self::ACTIVE->value => self::ACTIVE,
            self::ARCHIVED->value => self::ARCHIVED,
            self::DRAFT->value => self::DRAFT,
            default => throw new \InvalidArgumentException('Invalid status value'),
        };
    }

    public static function toArray(): array
    {
        return [
            self::ACTIVE->value => self::ACTIVE->title(),
            self::ARCHIVED->value => self::ARCHIVED->title(),
            self::DRAFT->value => self::DRAFT->title(),
        ];
    }
}
