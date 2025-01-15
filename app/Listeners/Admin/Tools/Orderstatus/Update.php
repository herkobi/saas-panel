<?php

namespace App\Listeners\Admin\Tools\Orderstatus;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Tools\Orderstatus\Update as Event;
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
            'orderstatus.updated',
            $event->changedBy,
            $event->orderstatus,
            [
                'new_orderstatus' => $event->newOrderstatus,
                'old_orderstatus' => $event->oldOrderstatus
            ]
        );

        Activity::create([
            'message' => 'orderstatus.updated',
            'log' => $this->logActivity(
                'user updated orderstatus of',
                $event->changedBy,
                $event->orderstatus,
                [
                    'old_orderstatus' => $event->oldOrderstatus,
                    'new_orderstatus' => $event->newOrderstatus
                ]
            ),
        ]);
    }
}
