<?php

namespace App\Listeners\Admin\Settings\Bacs;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Settings\Bacs\Delete as Event;
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
            'bacs.deleted',
            $event->deletedBy,
            $event->bacs,
            [
                'bacs_title' => $event->bacs->title,
            ]
        );

        Activity::create([
            'message' => 'bacs.deleted',
            'log' => $this->logActivity(
                ' user deleted bacs ',
                $event->deletedBy,
                $event->bacs,
                [
                    'bacs_title' => $event->bacs->title
                ]
            ),
        ]);
    }
}
