<?php

namespace App\Services\Admin\Feature;

use App\Repositories\FeatureRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Feature;

class FeatureService
{
    protected $repository;

    public function __construct(FeatureRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllFeatures(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    public function getFeatureById(string $id, bool $withoutGlobalScope = false): Feature
    {
        return $this->repository->getById($id, $withoutGlobalScope);
    }

    public function createFeature(array $data): Feature
    {
        return $this->repository->create($data);
    }

    public function updateFeature(string $id, array $data): Feature
    {
        return $this->repository->update($id, $data);
    }

    public function deleteFeature(string $id): void
    {
        $this->repository->delete($id);
    }
}
