<?php

namespace App\Listeners\Admin\Settings\Agreement;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Settings\Agreement\Delete as Event;
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
            'agreement.deleted',
            $event->deletedBy,
            $event->agreement,
            [
                'agreement_title' => $event->agreement->title,
            ]
        );

        Activity::create([
            'message' => 'agreement.deleted',
            'log' => $this->logActivity(
                ' user deleted agreement ',
                $event->deletedBy,
                $event->agreement,
                [
                    'agreement_title' => $event->agreement->title
                ]
            ),
        ]);
    }
}
