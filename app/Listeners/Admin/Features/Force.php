<?php

namespace App\Listeners\Admin\Features;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Features\Force as Event;
use App\Traits\LogActivity;

class Force
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
            'feature.forced',
            $event->forcedBy,
            $event->feature,
            [
                'feature_title' => $event->feature->name,
            ]
        );

        Activity::create([
            'message' => 'feature.forced',
            'log' => $this->logActivity(
                ' user forced feature ',
                $event->forcedBy,
                $event->feature,
                [
                    'feature_title' => $event->feature->name
                ]
            ),
        ]);
    }
}
