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
                } elseif (isset($featureData['quota']) && $featureData['quota'] !== null) {
                    $pivotData['charges'] = $featureData['quota'];
                }

                $plan->features()->attach($featureId, $pivotData);
            }
        }

        return $plan;
    }

    public function updatePlan(string $id, array $data): Plan
    {
        $plan = $this->getById($id);
        $consumable = $data['consumable'] ?? false;
        $periodicity = $consumable ? ($data['periodicity'] ?? null) : null;
        $periodicity_type = $consumable ? ($data['periodicity_type'] ?? null) : null;

        $plan->update([
            'consumable'       => $consumable,
            'name'             => $data['name'],
            'periodicity'      => $periodicity,
            'periodicity_type' => $periodicity_type,
            'quota'            => $data['quota'] ?? false,
            'postpaid'         => $data['postpaid'] ?? false,
        ]);

        return $plan;
    }


    public function deletePlan(string $id): bool|null
    {
        $plan = $this->getById($id);
        return $plan->delete();
    }

}
