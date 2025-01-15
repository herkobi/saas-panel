<?php

namespace App\Listeners\Admin\AccountGroup;

use App\Events\Admin\AccountGroup\Create as Event;
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
            'account.group.created',
            $event->createdBy,
            $event->accountgroup,
            [
                'accountgroup_title' => $event->accountgroup->title,
            ]
        );

        Activity::create([
            'message' => 'accountgroup.created',
            'log' => $this->logActivity(
                ' user created new accountgroup',
                $event->createdBy,
                $event->accountgroup,
                [
                    'accountgroup_title' => $event->accountgroup->title,
                ]
            ),
        ]);
    }
}
