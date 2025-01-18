<?php

namespace App\Actions\Admin\Order;

use App\Models\Order;
use App\Services\OrderService;
use App\Events\Admin\Order\PaymentApproved as Event;
use App\Traits\AuthUser;

class Approve
{
    use AuthUser;

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
        $this->initializeAuthUser();
    }

    public function execute(Order $order): bool
    {
        // Ödemeyi onayla
        $result = $this->orderService->approvePayment($order);

        if ($result) {
            // Subscription'ı yenile
            $order->tenant->subscription->renew();

            // Event'i tetikle
            event(new Event($order, $this->user));
        }

        return $result;
    }
}
