<?php

namespace App\Services\Admin;

use App\Models\Feature;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class FeatureService
{
    /**
     * Tüm özellikleri getir
     */
    public function getAllFeatures(): Collection
    {
        return Feature::withCount('planFeatures')
            ->latest()
            ->get();
    }

    /**
     * Sayfalanmış özellikleri getir
     */
    public function getPaginatedFeatures(int $perPage = 15): LengthAwarePaginator
    {
        return Feature::withCount('planFeatures')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Aktif özellikleri getir
     */
    public function getActiveFeatures(): Collection
    {
        return Feature::where('status', true)->get();
    }

    /**
     * Yeni özellik oluştur
     */
    public function create(array $data): Feature
    {
        return Feature::create($data);
    }

    /**
     * Özelliği güncelle
     */
    public function update(Feature $feature, array $data): Feature
    {
        $feature->update($data);
        return $feature;
    }

    /**
     * Özelliği sil
     */
    public function delete(Feature $feature): bool
    {
        // İlişki kontrolü
        if ($feature->planFeatures()->exists()) {
            return false;
        }

        return $feature->delete();
    }
}
