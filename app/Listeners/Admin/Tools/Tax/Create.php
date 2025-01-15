<?php

namespace App\Listeners\Admin\Tools\Tax;

use App\Events\Admin\Tools\Tax\Create as Event;
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
            'tax.created',
            $event->createdBy,
            $event->tax,
            [
                'tax_title' => $event->tax->title,
            ]
        );

        Activity::create([
            'message' => 'tax.created',
            'log' => $this->logActivity(
                ' user created new tax',
                $event->createdBy,
                $event->tax,
                [
                    'tax_title' => $event->tax->title,
                ]
            ),
        ]);
    }
}
