<?php

namespace App\Listeners\User\Account\InvoiceDetail;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\User\Account\InvoiceDetail\Update as Event;
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
            'invoice.detail.updated',
            $event->changedBy,
            $event->invoiceDetail,
            [
                'new_data' => $event->newData,
                'old_data' => $event->oldData
            ]
        );

        Activity::create([
            'message' => 'invoice.detail.updated',
            'log' => $this->logActivity(
                'user updated invoice detail of',
                $event->changedBy,
                $event->invoiceDetail,
                [
                    'old_data' => $event->oldData,
                    'new_data' => $event->newData
                ]
            ),
        ]);
    }
}
