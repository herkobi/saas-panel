<?php

namespace App\Listeners\Admin\Plans;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Plans\Restore as Event;
use App\Traits\LogActivity;

class Restore
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
            'plan.restored',
            $event->restoredBy,
            $event->plan,
            [
                'plan_title' => $event->plan->title,
            ]
        );

        Activity::create([
            'message' => 'plan.restoredBy',
            'log' => $this->logActivity(
                ' user restoredBy plan ',
                $event->restoredBy,
                $event->plan,
                [
                    'plan_title' => $event->plan->title
                ]
            ),
        ]);
    }
}
