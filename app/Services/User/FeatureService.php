<?php

namespace App\Services\User;

use App\Traits\AuthUser;
use Illuminate\Support\Facades\Cache;
use LucasDotVin\Soulbscription\Models\Feature;

class FeatureService
{
    use AuthUser;

    public function __construct()
    {
        $this->initializeAuthUser();
    }

    /**
     * Feature kullanılabilir mi kontrolü
     */
    public function checkFeatureAvailability(string $featureName): bool
    {
        // Feature'ın varlığını kontrol et
        $feature = $this->getFeature($featureName);
        if (!$feature) {
            return false;
        }

        // Tenant'ın bu feature'a erişimi var mı?
        if (!$this->user->tenant->hasFeature($featureName)) {
            return false;
        }

        // Tüketilebilir feature ise kalan kullanım hakkı kontrolü
        if ($feature->consumable) {
            return $this->user->tenant->canConsume($featureName);
        }

        return true;
    }

    /**
     * Feature'ı tüket
     */
    public function consumeFeature(string $featureName, float $amount = 1): void
    {
        $this->user->tenant->consume($featureName, $amount);
    }

    /**
     * Kalan kullanım hakkını getir
     */
    public function getRemainingUsage(string $featureName): ?float
    {
        if (!$this->checkFeatureAvailability($featureName)) {
            return null;
        }

        return $this->user->tenant->getRemainingCharges($featureName);
    }

    /**
     * Feature objesini getir
     */
    protected function getFeature(string $featureName): ?Feature
    {
        return Cache::remember(
            "feature.{$featureName}",
            now()->addHours(1),
            fn() => Feature::whereName($featureName)->first()
        );
    }
}
