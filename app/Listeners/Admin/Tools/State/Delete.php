<?php

namespace App\Listeners\Admin\Tools\State;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Tools\State\Delete as Event;
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
            'state.deleted',
            $event->deletedBy,
            $event->state,
            [
                'state_title' => $event->state->title,
            ]
        );

        Activity::create([
            'message' => 'state.deleted',
            'log' => $this->logActivity(
                ' user deleted state ',
                $event->deletedBy,
                $event->state,
                [
                    'state_title' => $event->state->title
                ]
            ),
        ]);
    }
}
