<?php

namespace App\Listeners\Admin\Features;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Features\Update as Event;
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
            'feature.updated',
            $event->changedBy,
            $event->feature,
            [
                'new_feature' => $event->newFeature,
                'old_feature' => $event->oldFeature
            ]
        );

        Activity::create([
            'message' => 'feature.updated',
            'log' => $this->logActivity(
                'user updated feature of',
                $event->changedBy,
                $event->feature,
                [
                    'old_feature' => $event->oldFeature,
                    'new_feature' => $event->newFeature
                ]
            ),
        ]);
    }
}
