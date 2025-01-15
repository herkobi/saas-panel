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

    public function handle(Event $event): void
    {
        $order = $event->order;
        $admin = $event->user;

        $this->loggingService->logUserAction(
            'order.payment.approved',
            $order->tenant->code,
            $order,
            [
                'approved_by' => $admin->id,
                'approved_by_name' => $admin->name,
                'payment_date' => $order->payment_date
            ]
        );

        Activity::create([
            'message' => 'order.payment.approved',
            'log' => $this->logActivity(
                'payment approved by',
                $order->tenant->code,
                $order->id,
                [
                    'approved_by' => $admin->id,
                    'approved_by_name' => $admin->name,
                    'payment_date' => $order->payment_date
                ]
            ),
        ]);
    }
}
