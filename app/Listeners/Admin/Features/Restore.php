<?php

namespace App\Listeners\Admin\Features;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Features\Restore as Event;
use App\Traits\LogActivity;

class Restore
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
            'feature.restored',
            $event->restoredBy,
            $event->feature,
            [
                'feature_title' => $event->feature->title,
            ]
        );

        Activity::create([
            'message' => 'feature.restoredBy',
            'log' => $this->logActivity(
                ' user restoredBy feature ',
                $event->restoredBy,
                $event->feature,
                [
                    'feature_title' => $event->feature->title
                ]
            ),
        ]);
    }
}
