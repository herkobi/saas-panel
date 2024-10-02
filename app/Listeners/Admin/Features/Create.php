<?php

namespace App\Listeners\Admin\Features;

use App\Events\Admin\Features\Create as Event;
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
            'feature.created',
            $event->createdBy,
            $event->feature,
            [
                'feature_title' => $event->feature,
            ]
        );

        Activity::create([
            'message' => 'feature.created',
            'log' => $this->logActivity(
                ' user created new feature',
                $event->createdBy,
                $event->feature,
                [
                    'feature_title' => $event->feature,
                ]
            ),
        ]);
    }
}
