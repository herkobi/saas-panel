<?php

namespace App\Services\Admin\Plan;

use App\Repositories\PlanRepository;
use Illuminate\Database\Eloquent\Collection;

class PlanService
{
    protected $repository;

    public function __construct(PlanRepository $repository)
    {
        $this->repository = $repository;
    }

}
