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

    public function getMainPlans(): Collection
    {
        return $this->repository->getMainPlans();
    }

    public function getTenantPlans(): Collection
    {
        return $this->repository->getTenantPlans();
    }

    public function getBasePlans(string $id): Collection
    {
        return $this->repository->getBasePlans($id);
    }

    public function getFrontPlans(): Collection
    {
        return $this->repository->getFrontPlans();
    }

    public function getPlanById(int $id, bool $withTrashed = false): Plan
    {
        return $this->repository->getById($id, $withTrashed);
    }

    public function createPlan(array $data): Plan
    {
        return $this->repository->createPlan($data);
    }

    public function updatePlan(int $id, array $data): Plan
    {
        return $this->repository->updatePlan($id, $data);
    }

    public function deletePlan(int $id): void
    {
        $this->repository->deletePlan($id);
    }

    public function restorePlan(int $id): void
    {
        $this->repository->restorePlan($id);
    }

    public function forceDelete(int $id): void
    {
        $this->repository->forceDelete($id);
    }

}
