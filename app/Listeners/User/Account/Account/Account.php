<?php

namespace App\Listeners\User\Account\Account;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\User\Account\Account\Account as Event;
use App\Traits\LogActivity;

class Account
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
            'invoice.detail.updated',
            $event->changedBy,
            $event->account,
            [
                'new_data' => $event->newData,
                'old_data' => $event->oldData
            ]
        );

        Activity::create([
            'message' => 'invoice.detail.updated',
            'log' => $this->logActivity(
                'user updated invoice detail of',
                $event->changedBy,
                $event->account,
                [
                    'old_data' => $event->oldData,
                    'new_data' => $event->newData
                ]
            ),
        ]);
    }
}
