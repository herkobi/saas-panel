<?php

namespace App\Listeners\User\Account\InvoiceDetail;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\User\Account\InvoiceDetail\Delete as Event;
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
            'invoice.detail.deleted',
            $event->deletedBy,
            $event->invoiceDetail,
            [
                'invoiceDetail_title' => $event->invoiceDetail->title,
            ]
        );

        Activity::create([
            'message' => 'invoice.detail.deleted',
            'log' => $this->logActivity(
                ' user deleted invoice detail ',
                $event->deletedBy,
                $event->invoiceDetail,
                [
                    'invoiceDetail_title' => $event->invoiceDetail->title
                ]
            ),
        ]);
    }
}
