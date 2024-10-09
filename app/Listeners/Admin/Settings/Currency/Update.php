<?php

namespace App\Listeners\Admin\Settings\Currency;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Settings\Currency\Update as Event;
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
            'currency.updated',
            $event->changedBy,
            $event->currency,
            [
                'new_currency' => $event->newCurrency,
                'old_currency' => $event->oldCurrency
            ]
        );

        Activity::create([
            'message' => 'currency.updated',
            'log' => $this->logActivity(
                'user updated currency of',
                $event->changedBy,
                $event->currency,
                [
                    'old_currency' => $event->oldCurrency,
                    'new_currency' => $event->newCurrency
                ]
            ),
        ]);
    }
}
