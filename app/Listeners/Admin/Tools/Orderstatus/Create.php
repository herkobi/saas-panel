<?php

namespace App\Listeners\Admin\Tools\Orderstatus;

use App\Events\Admin\Tools\Orderstatus\Create as Event;
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
            'orderstatus.created',
            $event->createdBy,
            $event->orderstatus,
            [
                'orderstatus_title' => $event->orderstatus->title,
            ]
        );

        Activity::create([
            'message' => 'orderstatus.created',
            'log' => $this->logActivity(
                ' user created new orderstatus',
                $event->createdBy,
                $event->orderstatus,
                [
                    'orderstatus_title' => $event->orderstatus->title,
                ]
            ),
        ]);
    }
}
