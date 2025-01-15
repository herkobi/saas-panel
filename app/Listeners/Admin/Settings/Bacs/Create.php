<?php

namespace App\Listeners\Admin\Settings\Bacs;

use App\Events\Admin\Settings\Bacs\Create as Event;
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
            'bacs.created',
            $event->createdBy,
            $event->bacs,
            [
                'bacs_title' => $event->bacs->title,
            ]
        );

        Activity::create([
            'message' => 'bacs.created',
            'log' => $this->logActivity(
                ' user created new bacs',
                $event->createdBy,
                $event->bacs,
                [
                    'bacs_title' => $event->bacs->title,
                ]
            ),
        ]);
    }
}
