<?php

namespace App\Listeners\User\Subscription;

use App\Events\User\Tenant\StatusChanged as Event;
use App\Models\Activity;
use App\Services\LoggingService;
use App\Traits\LogActivity;

class HandleStatusChanged
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
            'tenant.status.changed',
            $event->changedBy,
            $event->tenant,
            [
                'old_status' => $event->oldStatus->title(),
                'new_status' => $event->newStatus->title(),
            ]
        );

        Activity::create([
            'message' => 'tenant.status.changed',
            'log' => $this->logActivity(
                'tenant status changed',
                $event->changedBy,
                $event->tenant,
                [
                    'old_status' => $event->oldStatus->title(),
                    'new_status' => $event->newStatus->title(),
                ]
            ),
        ]);
    }
}
