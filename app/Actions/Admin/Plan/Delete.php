<?php

namespace App\Actions\Admin\Plan;

use App\Services\Admin\Plan\PlanService;
use App\Events\Admin\Plans\Delete as Event;
use App\Models\Plan;
use App\Traits\AuthUser;

class Delete
{
    use AuthUser;

    protected $planService;

    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
        $this->initializeAuthUser();
    }

    public function execute(int $id): Plan
    {
        $plan = $this->planService->getPlanById($id);
        $this->planService->deletePlan($id);
        event(new Event($plan, $this->user));
        return $plan;
    }
}
