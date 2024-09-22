<?php

namespace App\Services\Admin\Plan;

use App\Repositories\PlanRepository;

class PlanService
{
    protected $repository;

    public function __construct(PlanRepository $repository)
    {
        $this->repository = $repository;
    }

}
