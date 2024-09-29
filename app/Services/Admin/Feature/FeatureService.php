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

    public function getFeatureById(string $id): Feature
    {
        return $this->repository->getById($id);
    }

    public function createFeature(array $data): Feature
    {
        return $this->repository->createFeature($data);
    }

    public function updateFeature(string $id, array $data): Feature
    {
        return $this->repository->updateFeature($id, $data);
    }

    public function deleteFeature(string $id): void
    {
        $this->repository->deleteFeature($id);
    }
}
