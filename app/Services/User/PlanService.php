<?php

namespace App\Services\User;

use App\Repositories\PlanRepository;
use App\Models\Plan;

class PlanService
{
    protected $repository;

    public function __construct(PlanRepository $repository)
    {
        $this->repository = $repository;
    }

    public function switchPlan(int $id): Plan
    {
        return $this->repository->switchPlan($id);
    }
}
