<?php

namespace App\Actions\Admin\Order;

use App\Services\OrderService;
use App\Events\Admin\Order\PaymentApproved as Event;
use App\Models\Order;
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

    public function execute(string $id): Order
    {
        // Ödemeyi onayla
        $order = $this->orderService->getOrderForPanel($id);
        $result = $this->orderService->approvePayment($id);

        if ($result) {
            event(new Event($order, $this->user));
        }

        return $result;
    }
}
