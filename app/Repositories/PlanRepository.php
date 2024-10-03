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
        return Plan::create([
            'consumable'       => $data['consumable'] ?? false,
            'name'             => $data['name'],
            'periodicity'      => $data['periodicity'] ?? null,
            'periodicity_type' => $data['periodicity_type'] ?? null,
            'quota'            => $data['quota'] ?? false,
            'postpaid'         => $data['postpaid'] ?? false,
        ]);
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
