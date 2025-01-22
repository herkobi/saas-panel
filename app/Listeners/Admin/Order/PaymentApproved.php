<?php

namespace App\Listeners\Admin\Order;

use App\Events\Admin\Order\PaymentApproved as Event;
use App\Models\Activity;
use App\Services\LoggingService;
use App\Traits\LogActivity;

class PaymentApproved
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
            'order.payment.approved',
            $event->approvedBy,
            $event->order,
            [
                'page_title' => $event->order->code,
            ]
        );

        Activity::create([
            'message' => 'order.payment.approved',
            'log' => $this->logActivity(
                ' user approved payment',
                $event->approvedBy,
                $event->order,
                [
                    'order_code' => $event->order->code,
                ]
            ),
        ]);
    }
}
