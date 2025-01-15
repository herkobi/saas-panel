<?php

namespace App\Listeners\Admin\Settings\AgreementVersion;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Settings\AgreementVersion\Update as Event;
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
            'agreementversion.updated',
            $event->changedBy,
            $event->agreementversion,
            [
                'new_agreementversion' => $event->newAgreementVersion,
                'old_agreementversion' => $event->oldAgreementVersion
            ]
        );

        Activity::create([
            'message' => 'agreementversion.updated',
            'log' => $this->logActivity(
                'user updated agreementversion of',
                $event->changedBy,
                $event->agreementversion,
                [
                    'old_agreementversion' => $event->oldAgreementVersion,
                    'new_agreementversion' => $event->newAgreementVersion
                ]
            ),
        ]);
    }
}
