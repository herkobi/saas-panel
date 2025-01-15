<?php

namespace App\Listeners\Admin\Tools\State;

use App\Events\Admin\Tools\State\Create as Event;
use App\Models\Activity;
use App\Services\LoggingService;
use App\Traits\AuthUser;
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
            'state.created',
            $event->createdBy,
            $event->state,
            [
                'state_title' => $event->state->title,
            ]
        );

        Activity::create([
            'message' => 'state.created',
            'log' => $this->logActivity(
                ' user created new state',
                $event->createdBy,
                $event->state,
                [
                    'state_title' => $event->state->title,
                ]
            ),
        ]);
    }
}
