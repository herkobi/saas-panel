<?php

namespace App\Listeners\Admin\Plans;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Plans\Update as Event;
use App\Traits\LogActivity;

class Update
{
    use LogActivity;

    protected $loggingService;
    protected $activity;

    public function __construct(LoggingService $loggingService, Activity $activity)
    {
        $this->loggingService = $loggingService;
        $this->activity = $activity;
    }

    public function handle(Event $event)
    {
        $this->loggingService->logUserAction(
            'plan.updated',
            $event->changedBy,
            $event->plan,
            [
                'new_plan' => $event->newPlan,
                'old_plan' => $event->oldPlan
            ]
        );

        Activity::create([
            'message' => 'plan.updated',
            'log' => $this->logActivity(
                'user updated plan of',
                $event->changedBy,
                $event->plan,
                [
                    'old_plan' => $event->oldPlan,
                    'new_plan' => $event->newPlan
                ]
            ),
        ]);
    }
}
