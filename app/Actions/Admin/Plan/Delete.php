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

    public function execute(string $id): Plan
    {
        $feature = $this->planService->getPlanById($id);
        $this->planService->deletePlan($id);
        event(new Event($feature, $this->user));
        return $feature;
    }
}
