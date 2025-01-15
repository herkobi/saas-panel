<?php

namespace App\Listeners\Admin\Settings\Agreement;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Settings\Agreement\Update as Event;
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
            'agreement.updated',
            $event->changedBy,
            $event->agreement,
            [
                'new_agreement' => $event->newAgreement,
                'old_agreement' => $event->oldAgreement
            ]
        );

        Activity::create([
            'message' => 'agreement.updated',
            'log' => $this->logActivity(
                'user updated agreement of',
                $event->changedBy,
                $event->agreement,
                [
                    'old_agreement' => $event->oldAgreement,
                    'new_agreement' => $event->newAgreement
                ]
            ),
        ]);
    }
}
