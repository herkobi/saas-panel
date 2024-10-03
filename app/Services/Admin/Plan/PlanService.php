<?php

namespace App\Services\Admin\Plan;

use App\Models\Plan;
use App\Repositories\PlanRepository;
use Illuminate\Database\Eloquent\Collection;

class PlanService
{
    protected $repository;

    public function __construct(PlanRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllPlans(): Collection
    {
        return $this->repository->getAll();
    }

    public function getPlanById(string $id): Plan
    {
        return $this->repository->getById($id);
    }

    public function createPlan(array $data): Plan
    {
        return $this->repository->createPlan($data);
    }

    public function updatePlan(string $id, array $data): Plan
    {
        return $this->repository->updatePlan($id, $data);
    }

    public function deletePlan(string $id): void
    {
        $this->repository->deletePlan($id);
    }

}
