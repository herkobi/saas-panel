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

        // Sadece new_tenant true ise güncelle
        if ($event->order->tenant->new_tenant) {
            $event->order->tenant->new_tenant = false;
        }

        // first_paid_plan null ise order'daki plan ile güncelle
        if (is_null($event->order->tenant->first_paid_plan)) {
            $event->order->tenant->first_paid_plan = $event->order->plan_id;
        }

        // Eğer değişiklik varsa kaydet
        if ($event->order->tenant->isDirty()) {
            $event->order->tenant->save();
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
