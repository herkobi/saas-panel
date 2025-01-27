<?php

namespace App\Repositories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Collection;

class PlanRepository
{
    protected $model = Plan::class;

    public function getAll(): Collection
    {
        return $this->model::withTrashed()->get();
    }

    public function getMainPlans(): Collection
    {
        return $this->model::where('base', null)->withTrashed()->get();
    }

    public function getTenantPlans(): Collection
    {
        return $this->model::whereNot('base', null)->withTrashed()->get();
    }

    public function getBasePlans(string $id): Collection
    {
        return $this->model::where('base', $id)->withoutTrashed()->with('features')->get();
    }

    public function getFrontPlans(): Collection
    {
        return $this->model::where('base', null)->with('features')->get();
    }

    public function getById(string $id, bool $withTrashed = false): Plan
    {
        $query = $this->model::query();

        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->findOrFail($id);
    }

    public function createPlan(array $data): Plan
    {
        $plan = $this->model::create([
            'base' => $data['base'] ?? null,
            'name' => $data['name'],
            'description' => $data['desc'] ?? null,
            'periodicity_type' => $data['periodicity_type'] ?? null,
            'periodicity' => $data['periodicity'] ?? null,
            'price' => $data['price'] ?? 0,
            'grace_days' => $data['grace_days'] ?? 0,
            'currency_id' => $data['currency_id'] ?? null,
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
            'base' => $data['base'] ?? null,
            'name' => $data['name'],
            'description' => $data['desc'] ?? null,
            'periodicity_type' => $data['periodicity_type'] ?? null,
            'periodicity' => $data['periodicity'] ?? null,
            'price' => $data['price'] ?? 0,
            'grace_days' => $data['grace_days'] ?? 0,
            'currency_id' => $data['currency_id'] ?? null,
        ]);

        $currentFeatures = $plan->features()->pluck('charges', 'features.id')->toArray();
        $newFeatures = $data['features'] ?? [];

        foreach ($newFeatures as $featureId => $featureData) {
            $limit = $featureData['limit'] ?? null;

            if (!isset($currentFeatures[$featureId])) {
                $plan->features()->attach($featureId, ['charges' => $limit]);
            } elseif ($currentFeatures[$featureId] != $limit) {
                $plan->features()->updateExistingPivot($featureId, ['charges' => $limit]);
            }

            unset($currentFeatures[$featureId]);
        }

        if (!empty($currentFeatures)) {
            $plan->features()->detach(array_keys($currentFeatures));
        }

        return $plan;
    }

    public function deletePlan(int $id): bool|null
    {
        $plan = $this->getById($id);
        $plan->features()->detach();
        return $plan->delete();
    }

    public function restorePlan(int $id): bool|null
    {
        $plan = $this->getById($id, true);
        return $plan->restore();
    }

    public function forceDelete(int $id): bool|null
    {
        $plan = $this->getById($id, true);
        return $plan->forceDelete();
    }

    public function switchPlan(int $id): Plan
    {
        return true;
    }

}
