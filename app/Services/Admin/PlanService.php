<?php

namespace App\Services\Admin;

use App\Models\Plan;
use App\Models\PlanFeature;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PlanService
{
    /**
     * Tüm planları getir
     */
    public function getAllPlans(): Collection
    {
        return Plan::with('planFeatures.feature')
            ->latest()
            ->get();
    }

    /**
     * Sayfalanmış planları getir
     */
    public function getPaginatedPlans(int $perPage = 15): LengthAwarePaginator
    {
        return Plan::with('planFeatures.feature')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Aktif planları getir
     */
    public function getActivePlans(): Collection
    {
        return Plan::where('status', true)
            ->with('planFeatures.feature')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * Detaylı plan bilgisini getir
     */
    public function getPlanWithDetails(Plan $plan): Plan
    {
        return $plan->load('planFeatures.feature');
    }

    /**
     * Yeni plan oluştur
     */
    public function create(array $planData, array $featuresData = []): Plan
    {
        return DB::transaction(function () use ($planData, $featuresData) {
            $plan = Plan::create($planData);

            foreach ($featuresData as $featureData) {
                $planFeature = new PlanFeature($featureData);
                $planFeature->validate(); // Validasyon uygula
                $plan->planFeatures()->save($planFeature);
            }

            return $plan;
        });
    }

    /**
     * Planı güncelle
     */
    public function update(Plan $plan, array $planData, array $featuresData = []): Plan
    {
        return DB::transaction(function () use ($plan, $planData, $featuresData) {
            $plan->update($planData);

            // Mevcut özellikleri temizle
            $plan->planFeatures()->delete();

            // Yeni özellikleri ekle
            foreach ($featuresData as $featureData) {
                $planFeature = new PlanFeature($featureData);
                $planFeature->validate(); // Validasyon uygula
                $plan->planFeatures()->save($planFeature);
            }

            return $plan;
        });
    }

    /**
     * Planı sil
     */
    public function delete(Plan $plan): bool
    {
        return DB::transaction(function () use ($plan) {
            $plan->planFeatures()->delete();
            return $plan->delete();
        });
    }
}
