<?php

namespace App\Listeners\Admin\Settings\Bacs;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Settings\Bacs\Update as Event;
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
            'bacs.updated',
            $event->changedBy,
            $event->bacs,
            [
                'new_bacs' => $event->newBacs,
                'old_bacs' => $event->oldBacs
            ]
        );

        Activity::create([
            'message' => 'bacs.updated',
            'log' => $this->logActivity(
                'user updated bacs of',
                $event->changedBy,
                $event->bacs,
                [
                    'old_bacs' => $event->oldBacs,
                    'new_bacs' => $event->newBacs
                ]
            ),
        ]);
    }
}
