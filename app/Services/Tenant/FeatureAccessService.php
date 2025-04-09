<?php

namespace App\Services\Tenant;

use App\Models\Feature;
use App\Models\Tenant;

class FeatureAccessService
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {
    }

    /**
     * Tenant'ın belirli bir özelliğe erişimi olup olmadığını kontrol eder
     */
    public function canAccess(Tenant $tenant, string $featureSlug): bool
    {
        // Önce feature'ı bul
        $feature = $this->getFeatureBySlug($featureSlug);
        if (!$feature) {
            return false;
        }

        // Aktif aboneliği al
        $activeSubscription = $this->subscriptionService->getActiveSubscription($tenant);
        if (!$activeSubscription) {
            return false;
        }

        // Aboneliğin planının özelliklerini kontrol et
        $planFeature = $activeSubscription->plan->getFeature($feature->id);

        // Eğer plan bu özelliği içermiyorsa, erişim yok
        if (!$planFeature) {
            return false;
        }

        return true;
    }

    /**
     * Slug ile özelliği getirir
     */
    public function getFeatureBySlug(string $slug)
    {
        return Feature::where('slug', $slug)->where('status', true)->first();
    }

    /**
     * Feature ID ile özelliği getirir
     */
    public function getFeatureById(int $featureId)
    {
        return Feature::where('id', $featureId)->where('status', true)->first();
    }
}
