<?php

namespace App\Listeners\Admin\Features;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Features\Delete as Event;
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
            'feature.deleted',
            $event->deletedBy,
            $event->feature,
            [
                'feature_title' => $event->feature->name,
            ]
        );

        Activity::create([
            'message' => 'feature.deleted',
            'log' => $this->logActivity(
                ' user deleted feature ',
                $event->deletedBy,
                $event->feature,
                [
                    'feature_title' => $event->feature->name
                ]
            ),
        ]);
    }
}
