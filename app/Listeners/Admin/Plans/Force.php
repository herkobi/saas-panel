<?php

namespace App\Listeners\Admin\Plans;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Plans\Force as Event;
use App\Traits\LogActivity;

class Force
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
            'plan.forced',
            $event->forcedBy,
            $event->plan,
            [
                'plan_title' => $event->plan->title,
            ]
        );

        Activity::create([
            'message' => 'plan.forced',
            'log' => $this->logActivity(
                ' user forced plan ',
                $event->forcedBy,
                $event->plan,
                [
                    'plan_title' => $event->plan->title
                ]
            ),
        ]);
    }
}
