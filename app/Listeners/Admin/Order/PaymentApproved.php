<?php

namespace App\Listeners\Admin\Order;

use App\Events\Admin\Order\PaymentApproved as Event;
use App\Models\Activity;
use App\Services\LoggingService;
use App\Traits\LogActivity;
use LucasDotVin\Soulbscription\Models\Scopes\SuppressingScope;
use LucasDotVin\Soulbscription\Models\Subscription;

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

        $subscription = Subscription::where('subscriber_id', $event->order->tenant_id)
            ->where('subscriber_type', get_class($event->order->tenant))
            ->withoutGlobalScope(SuppressingScope::class)
            ->latest('started_at')
            ->first();

        if ($subscription) {
            $subscription->update(['suppressed_at' => null]);
            //$subscription->renew();
        }

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
