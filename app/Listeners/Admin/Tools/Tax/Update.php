<?php

namespace App\Listeners\Admin\Tools\Tax;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Tools\Tax\Update as Event;
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
            'tax.updated',
            $event->changedBy,
            $event->tax,
            [
                'new_tax' => $event->newTax,
                'old_tax' => $event->oldTax
            ]
        );

        Activity::create([
            'message' => 'tax.updated',
            'log' => $this->logActivity(
                'user updated tax of',
                $event->changedBy,
                $event->tax,
                [
                    'old_tax' => $event->oldTax,
                    'new_tax' => $event->newTax
                ]
            ),
        ]);
    }
}
