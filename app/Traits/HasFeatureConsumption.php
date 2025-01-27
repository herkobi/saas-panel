<?php

namespace App\Traits;

use App\Services\User\FeatureService;

trait HasFeatureConsumption
{
    protected ?FeatureService $featureManager = null;

    protected function getFeatureService(): FeatureService
    {
        return $this->featureManager ??= app(FeatureService::class);
    }

    public function canUseFeature(string $featureName): bool
    {
        return $this->getFeatureService()->checkFeatureAvailability($featureName);
    }

    public function useFeature(string $featureName, float $amount = 1): void
    {
        $this->getFeatureService()->consumeFeature($featureName, $amount);
    }

    public function getFeatureUsage(string $featureName): ?float
    {
        return $this->getFeatureService()->getRemainingUsage($featureName);
    }
}
