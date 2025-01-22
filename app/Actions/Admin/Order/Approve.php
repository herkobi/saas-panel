<?php

namespace App\Actions\Admin\Order;

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

    public function execute(string $id): bool
    {
        // Ödemeyi onayla
        $result = $this->orderService->approvePayment($id);
        $order = $this->orderService->getOrderById($id);

        if ($result) {
            event(new Event($order, $this->user));
        }

        return $result;
    }
}
