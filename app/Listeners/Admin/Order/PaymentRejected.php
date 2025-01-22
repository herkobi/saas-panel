<?php

namespace App\Listeners\Admin\Order;

use App\Models\Activity;
use App\Services\LoggingService;
use App\Events\Admin\Order\PaymentRejected as Event;
use App\Traits\LogActivity;

class PaymentRejected
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
            'order.payment.rejected',
            $event->rejectedBy,
            $event->order,
            [
                'order_code' => $event->order->code,
            ]
        );

        Activity::create([
            'message' => 'order.payment.rejected',
            'log' => $this->logActivity(
                ' user rejected payment ',
                $event->rejectedBy,
                $event->order,
                [
                    'order_code' => $event->order->code
                ]
            ),
        ]);
    }
}
