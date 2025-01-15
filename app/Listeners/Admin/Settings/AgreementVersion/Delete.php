<?php

namespace App\Listeners\Admin\Settings\AgreementVersion;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Settings\AgreementVersion\Delete as Event;
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
            'agreementversion.deleted',
            $event->deletedBy,
            $event->agreementversion,
            [
                'agreementversion_title' => $event->agreementversion->version,
            ]
        );

        Activity::create([
            'message' => 'agreementversion.deleted',
            'log' => $this->logActivity(
                ' user deleted agreementversion ',
                $event->deletedBy,
                $event->agreementversion,
                [
                    'agreementversion_title' => $event->agreementversion->version
                ]
            ),
        ]);
    }
}
