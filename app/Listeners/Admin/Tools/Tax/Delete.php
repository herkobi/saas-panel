<?php

namespace App\Listeners\Admin\Tools\Tax;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Tools\Tax\Delete as Event;
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
            'tax.deleted',
            $event->deletedBy,
            $event->tax,
            [
                'tax_title' => $event->tax->title,
            ]
        );

        Activity::create([
            'message' => 'tax.deleted',
            'log' => $this->logActivity(
                ' user deleted tax ',
                $event->deletedBy,
                $event->tax,
                [
                    'tax_title' => $event->tax->title
                ]
            ),
        ]);
    }
}
