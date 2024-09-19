<?php

namespace App\Listeners\User\Account\InvoiceDetail;

use App\Events\User\Account\InvoiceDetail\Create as Event;
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
            'invoiceDetail.created',
            $event->createdBy,
            $event->invoiceDetail,
            [
                'page_title' => $event->invoiceDetail->title,
            ]
        );

        Activity::create([
            'message' => 'invoiceDetail.created',
            'log' => $this->logActivity(
                ' user created new invoice detail',
                $event->createdBy,
                $event->invoiceDetail,
                [
                    'page_title' => $event->invoiceDetail->title,
                ]
            ),
        ]);
    }
}
