<?php

namespace App\Listeners\User\Account\Order;

use App\Events\User\Account\Order\PlanSwitch;
use App\Models\Activity;
use App\Services\LoggingService;
use App\Traits\LogActivity;

class HandlePlanSwitch
{
    use LogActivity;

    protected $loggingService;
    protected $activity;

    public function __construct(LoggingService $loggingService, Activity $activity)
    {
        $this->loggingService = $loggingService;
        $this->activity = $activity;
    }

    public function handle(PlanSwitch $event)
    {
        $this->loggingService->logUserAction(
            'plan.switch.order',
            $event->switchedBy,
            $event->order,
            [
                'old_plan' => $event->order->switch_data['old_plan_id'],
                'new_plan' => $event->order->plan_id
            ]
        );

        Activity::create([
            'message' => 'plan.switch.order',
            'log' => $this->logActivity(
                'user created plan switch order',
                $event->switchedBy,
                $event->order,
                [
                    'old_plan' => $event->order->switch_data['old_plan_id'],
                    'new_plan' => $event->order->plan_id
                ]
            ),
        ]);
    }
}
