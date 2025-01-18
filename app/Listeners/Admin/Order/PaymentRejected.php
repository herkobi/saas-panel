<?php

namespace App\Listeners\Admin\Order;

use App\Events\Admin\Order\PaymentRejected as Event;
use App\Models\Activity;
use App\Services\LoggingService;
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

    public function handle(Event $event): void
    {
        $order = $event->order;
        $admin = $event->user;

        $this->loggingService->logUserAction(
            'order.payment.rejected',
            $order->tenant->code,
            $order,
            [
                'rejected_by' => $admin->id,
                'rejected_by_name' => $admin->name,
                'plan_id' => $order->plan_id,
                'amount' => $order->amount
            ]
        );

        Activity::create([
            'message' => 'order.payment.rejected',
            'log' => $this->logActivity(
                'payment rejected by',
                $order->tenant->code,
                $order->id,
                [
                    'rejected_by' => $admin->id,
                    'rejected_by_name' => $admin->name,
                    'plan_id' => $order->plan_id,
                    'amount' => $order->amount
                ]
            ),
        ]);
    }
}
