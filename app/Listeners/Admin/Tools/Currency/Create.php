<?php

namespace App\Listeners\Admin\Tools\Currency;

use App\Events\Admin\Tools\Currency\Create as Event;
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
            'currency.created',
            $event->createdBy,
            $event->currency,
            [
                'currency_title' => $event->currency->title,
            ]
        );

        Activity::create([
            'message' => 'currency.created',
            'log' => $this->logActivity(
                ' user created new currency',
                $event->createdBy,
                $event->currency,
                [
                    'currency_title' => $event->currency->title,
                ]
            ),
        ]);
    }
}
