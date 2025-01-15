<?php

namespace App\Listeners\Admin\Settings\Agreement;

use App\Events\Admin\Settings\Agreement\Accept as Event;
use App\Models\Activity;
use App\Services\LoggingService;
use App\Traits\LogActivity;

class Accept
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
            'agreement.accepted',
            $event->acceptedBy,
            $event->agreement
        );

        Activity::create([
            'message' => 'agreement.accepted',
            'log' => $this->logActivity(
                'user accepted agreement',
                $event->acceptedBy,
                $event->agreement
            ),
        ]);
    }
}
