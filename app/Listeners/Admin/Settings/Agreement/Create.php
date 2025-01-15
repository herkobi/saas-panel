<?php

namespace App\Listeners\Admin\Settings\Agreement;

use App\Events\Admin\Settings\Agreement\Create as Event;
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
            'agreement.created',
            $event->createdBy,
            $event->agreement,
            [
                'agreement_title' => $event->agreement->title,
            ]
        );

        Activity::create([
            'message' => 'agreement.created',
            'log' => $this->logActivity(
                ' user created new agreement',
                $event->createdBy,
                $event->agreement,
                [
                    'agreement_title' => $event->agreement->title,
                ]
            ),
        ]);
    }
}
