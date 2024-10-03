<?php

namespace App\Listeners\Admin\Plans;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Plans\Delete as Event;
use App\Traits\LogActivity;

class Delete
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
            'plan.deleted',
            $event->deletedBy,
            $event->plan,
            [
                'plan_title' => $event->plan->title,
            ]
        );

        Activity::create([
            'message' => 'plan.deleted',
            'log' => $this->logActivity(
                ' user deleted plan ',
                $event->deletedBy,
                $event->plan,
                [
                    'plan_title' => $event->plan->title
                ]
            ),
        ]);
    }
}
