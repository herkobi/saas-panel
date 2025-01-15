<?php

namespace App\Listeners\Admin\Settings\AgreementVersion;

use App\Enums\UserType;
use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Settings\AgreementVersion\Publish as Event;
use App\Traits\LogActivity;

class Publish
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
        // Listener'da
        $this->loggingService->logUserAction(
            'agreementversion.published',
            $event->publishedBy,
            $event->agreementVersion,
            [
                'old_version_id' => $event->oldVersion ? $event->oldVersion->id : null,
                'old_version_number' => $event->oldVersion ? $event->oldVersion->version : null,
                'require_acceptance' => $event->agreementVersion->require_acceptance,
                'send_notification' => $event->agreementVersion->send_notification,
                ...($event->agreementVersion->agreement->user_type === UserType::USER
                        ? ['block_access' => $event->agreementVersion->block_access]
                        : [])
            ]
        );

        Activity::create([
            'message' => 'agreementversion.published',
            'log' => $this->logActivity(
                'user published agreementversion of',
                $event->publishedBy,
                $event->agreementVersion,
                [
                    'old_version_id' => $event->oldVersion ? $event->oldVersion->id : null,
                    'old_version_number' => $event->oldVersion ? $event->oldVersion->version : null
                ]
            ),
        ]);
    }
}
