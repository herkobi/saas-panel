<?php

namespace App\Listeners\Admin\AccountGroup;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\AccountGroup\Update as Event;
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
            'accountgroup.updated',
            $event->changedBy,
            $event->accountgroup,
            [
                'new_accountgroup' => $event->newAccountGroup,
                'old_accountgroup' => $event->oldAccountGroup
            ]
        );

        Activity::create([
            'message' => 'accountgroup.updated',
            'log' => $this->logActivity(
                'user updated accountgroup of',
                $event->changedBy,
                $event->accountgroup,
                [
                    'old_accountgroup' => $event->oldAccountGroup,
                    'new_accountgroup' => $event->newAccountGroup
                ]
            ),
        ]);
    }
}
