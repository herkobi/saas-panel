<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use LucasDotVin\Soulbscription\Models\Feature;

class FeatureRepository
{
    protected $model = Feature::class;

    public function getAll(): Collection
    {
        return Feature::withTrashed()->get();
    }

    public function getFeaturesForPlans(): Collection
    {
        return Feature::withoutTrashed()->get();
    }

    public function getById(int $id, bool $withTrashed = false): Feature
    {
        $query = Feature::query();

        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->findOrFail($id);
    }

    public function createFeature(array $data): Feature
    {
        return Feature::create([
            'consumable'       => $data['consumable'] ?? false,
            'name'             => $data['name'],
            'periodicity'      => $data['periodicity'] ?? null,
            'periodicity_type' => $data['periodicity_type'] ?? null,
            'quota'            => $data['quota'] ?? false,
            'postpaid'         => $data['postpaid'] ?? false,
        ]);
    }

    public function updateFeature(string $id, array $data): Feature
    {
        $feature = $this->getById($id);
        $consumable = $data['consumable'] ?? false;
        $periodicity = $consumable ? ($data['periodicity'] ?? null) : null;
        $periodicity_type = $consumable ? ($data['periodicity_type'] ?? null) : null;

        $feature->update([
            'consumable'       => $consumable,
            'name'             => $data['name'],
            'periodicity'      => $periodicity,
            'periodicity_type' => $periodicity_type,
            'quota'            => $data['quota'] ?? false,
            'postpaid'         => $data['postpaid'] ?? false,
        ]);

        return $feature;
    }

    public function deleteFeature(string $id): bool|null
    {
        $feature = $this->getById($id);
        $feature->plans()->detach();
        return $feature->delete();
    }
    public function restoreFeature(int $id): bool|null
    {
        $plan = $this->getById($id, true);
        return $plan->restore();
    }

    public function forceDelete(int $id): bool|null
    {
        $plan = $this->getById($id, true);
        return $plan->forceDelete();
    }
}
