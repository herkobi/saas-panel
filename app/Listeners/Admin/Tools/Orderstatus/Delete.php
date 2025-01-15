<?php

namespace App\Listeners\Admin\Tools\Orderstatus;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Tools\Orderstatus\Delete as Event;
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
            'orderstatus.deleted',
            $event->deletedBy,
            $event->orderstatus,
            [
                'orderstatus_title' => $event->orderstatus->title,
            ]
        );

        Activity::create([
            'message' => 'orderstatus.deleted',
            'log' => $this->logActivity(
                ' user deleted orderstatus ',
                $event->deletedBy,
                $event->orderstatus,
                [
                    'orderstatus_title' => $event->orderstatus->title
                ]
            ),
        ]);
    }
}
