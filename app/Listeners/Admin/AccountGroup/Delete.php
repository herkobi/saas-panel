<?php

namespace App\Listeners\Admin\AccountGroup;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\AccountGroup\Delete as Event;
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
            'accountgroup.deleted',
            $event->deletedBy,
            $event->accountgroup,
            [
                'accountgroup_title' => $event->accountgroup->title,
            ]
        );

        Activity::create([
            'message' => 'accountgroup.deleted',
            'log' => $this->logActivity(
                ' user deleted accountgroup ',
                $event->deletedBy,
                $event->accountgroup,
                [
                    'accountgroup_title' => $event->accountgroup->title
                ]
            ),
        ]);
    }
}
