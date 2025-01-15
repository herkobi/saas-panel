<?php

namespace App\Listeners\Admin\Tools\Currency;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Tools\Currency\Delete as Event;
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
            'currency.deleted',
            $event->deletedBy,
            $event->currency,
            [
                'currency_title' => $event->currency->title,
            ]
        );

        Activity::create([
            'message' => 'currency.deleted',
            'log' => $this->logActivity(
                ' user deleted currency ',
                $event->deletedBy,
                $event->currency,
                [
                    'currency_title' => $event->currency->title
                ]
            ),
        ]);
    }
}
