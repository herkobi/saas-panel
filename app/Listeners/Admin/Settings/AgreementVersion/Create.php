<?php

namespace App\Listeners\Admin\Settings\AgreementVersion;

use App\Events\Admin\Settings\AgreementVersion\Create as Event;
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
            'agreementversion.created',
            $event->createdBy,
            $event->agreementversion,
            [
                'agreementversion_title' => $event->agreementversion->title,
            ]
        );

        Activity::create([
            'message' => 'agreementversion.created',
            'log' => $this->logActivity(
                ' user created new agreementversion',
                $event->createdBy,
                $event->agreementversion,
                [
                    'agreementversion_title' => $event->agreementversion->title,
                ]
            ),
        ]);
    }
}
