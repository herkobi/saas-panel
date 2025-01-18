<?php

namespace App\Actions\Admin\Order;

use App\Models\Order;
use App\Services\OrderService;
use App\Events\Admin\Order\PaymentRejected as Event;
use App\Traits\AuthUser;

class Reject
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
        $result = $this->orderService->rejectPayment($order);

        if ($result) {
            event(new Event($order, $this->user));
        }

        return $result;
    }
}
