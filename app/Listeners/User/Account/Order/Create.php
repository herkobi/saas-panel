<?php

namespace App\Listeners\User\Account\Order;

use App\Events\User\Account\Order\Create as Event;
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
        if ($event->order->orderstatus->code === 'APPROVED') {
            $event->order->tenant->subscribeTo($event->order->plan);
        }

        $this->loggingService->logUserAction(
            'order.created',
            $event->createdBy,
            $event->order,
            [
                'order_code' => $event->order,
            ]
        );

        Activity::create([
            'message' => 'plan.created',
            'log' => $this->logActivity(
                ' user created new plan',
                $event->createdBy,
                $event->order,
                [
                    'order_code' => $event->order,
                ]
            ),
        ]);
    }
}
