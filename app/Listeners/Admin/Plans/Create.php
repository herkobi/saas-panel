<?php

namespace App\Listeners\Admin\Plans;

use App\Events\Admin\Plans\Create as Event;
use App\Models\Activity;
use App\Services\LoggingService;
use App\Traits\LogActivity;

class Create
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
            'plan.created',
            $event->createdBy,
            $event->plan,
            [
                'plan_title' => $event->plan,
            ]
        );

        Activity::create([
            'message' => 'plan.created',
            'log' => $this->logActivity(
                ' user created new plan',
                $event->createdBy,
                $event->plan,
                [
                    'plan_title' => $event->plan,
                ]
            ),
        ]);
    }
}
