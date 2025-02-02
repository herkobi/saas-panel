<?php

namespace App\Actions\Admin\Plan;

use App\Services\Admin\Plan\PlanService;
use App\Events\Admin\Plans\Update as Event;
use App\Models\Plan;
use App\Traits\AuthUser;

class Update
{
    use AuthUser;

    protected $planService;

    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
        $this->initializeAuthUser();
    }

    public function execute(int $id, array $data): Plan
    {
        $oldPlan = $this->planService->getPlanById($id);
        $plan = $this->planService->updatePlan($id, $data);
        $newPlan = $this->planService->getPlanById($id);
        event(new Event($plan, $this->user, $oldPlan, $newPlan));
        return $plan;
    }
}
