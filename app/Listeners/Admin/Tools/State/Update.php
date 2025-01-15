<?php

namespace App\Listeners\Admin\Tools\State;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Tools\State\Update as Event;
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
            'state.updated',
            $event->changedBy,
            $event->state,
            [
                'new_state' => $event->newState,
                'old_state' => $event->oldState
            ]
        );

        Activity::create([
            'message' => 'state.updated',
            'log' => $this->logActivity(
                'user updated state of',
                $event->changedBy,
                $event->state,
                [
                    'old_state' => $event->oldState,
                    'new_state' => $event->newState
                ]
            ),
        ]);
    }
}
