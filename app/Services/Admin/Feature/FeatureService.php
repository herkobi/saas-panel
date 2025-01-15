<?php

namespace App\Services\Admin\Feature;

use App\Repositories\FeatureRepository;
use LucasDotVin\Soulbscription\Models\Feature;
use Illuminate\Database\Eloquent\Collection;

class FeatureService
{
    protected $repository;

    public function __construct(FeatureRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllFeatures(): Collection
    {
        return $this->repository->getAll();
    }

    public function getFeaturesForPlans(): Collection
    {
        return $this->repository->getFeaturesForPlans();
    }

    public function getFeatureById(int $id, bool $withTrashed = false): Feature
    {
        return $this->repository->getById($id, $withTrashed);
    }

    public function createFeature(array $data): Feature
    {
        return $this->repository->createFeature($data);
    }

    public function updateFeature(int $id, array $data): Feature
    {
        return $this->repository->updateFeature($id, $data);
    }

    public function deleteFeature(int $id): void
    {
        $this->repository->deleteFeature($id);
    }

    public function restoreFeature(int $id): void
    {
        $this->repository->restoreFeature($id);
    }

    public function forceDelete(int $id): void
    {
        $this->repository->forceDelete($id);
    }
}
