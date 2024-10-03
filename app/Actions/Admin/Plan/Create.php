<?php

namespace App\Actions\Admin\Plan;

use App\Services\Admin\Plan\PlanService;
use App\Events\Admin\Plans\Create as Event;
use App\Traits\AuthUser;
use App\Models\Plan;

class Create
{
    use AuthUser;

    protected $planService;

    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
        $this->initializeAuthUser();
    }

    public function execute(array $data): Plan
    {
        $feature = $this->planService->createPlan($data);
        event(new Event($feature, $this->user));
        return $feature;
    }
}
