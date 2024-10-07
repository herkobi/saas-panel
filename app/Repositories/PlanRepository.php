<?php

namespace App\Repositories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Collection;

class PlanRepository
{
    protected $model = Plan::class;

    public function getAll(): Collection
    {
        return Plan::all();
    }

    public function getById(string $id): Plan
    {
        return Plan::findOrFail($id);
    }

    public function createPlan(array $data): Plan
    {
        $plan = Plan::create([
            'name' => $data['name'],
            'description' => $data['desc'] ?? null,
            'periodicity_type' => $data['periodicity_type'] ?? null,
            'periodicity' => $data['periodicity'] ?? null,
            'price' => $data['price'] ?? 0,
            'grace_days' => $data['grace_days'] ?? 0,
        ]);

        if (isset($data['features']) && is_array($data['features'])) {
            foreach ($data['features'] as $featureId => $featureData) {
                $pivotData = [];
                if (isset($featureData['limit']) && $featureData['limit'] !== null) {
                    $pivotData['charges'] = $featureData['limit'];
                }

                $plan->features()->attach($featureId, $pivotData);
            }
        }

        return $plan;
    }

    public function updatePlan(string $id, array $data): Plan
    {
        $plan = $this->getById($id);
        $plan->update([
            'name' => $data['name'],
            'description' => $data['desc'] ?? null,
            'periodicity_type' => $data['periodicity_type'] ?? null,
            'periodicity' => $data['periodicity'] ?? null,
            'price' => $data['price'] ?? 0,
            'grace_days' => $data['grace_days'] ?? 0,
        ]);

        $currentFeatures = $plan->features()->pluck('charges', 'features.id')->toArray();
        $newFeatures = $data['features'] ?? [];

        foreach ($newFeatures as $featureId => $featureData) {
            $limit = $featureData['limit'] ?? null;

            if (!isset($currentFeatures[$featureId])) {
                // Yeni feature ekleniyor
                $plan->features()->attach($featureId, ['charges' => $limit]);
            } elseif ($currentFeatures[$featureId] != $limit) {
                // Mevcut feature güncelleniyor
                $plan->features()->updateExistingPivot($featureId, ['charges' => $limit]);
            }

            unset($currentFeatures[$featureId]);
        }

        // Kalan feature'lar kaldırılıyor
        if (!empty($currentFeatures)) {
            $plan->features()->detach(array_keys($currentFeatures));
        }

        return $plan;
    }


    public function deletePlan(string $id): bool|null
    {
        $plan = $this->getById($id);
        return $plan->delete();
    }

}
