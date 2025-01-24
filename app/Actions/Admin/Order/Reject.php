<?php

namespace App\Actions\Admin\Order;

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

    public function execute(string $id): bool
    {
        // Ödemeyi reddet
        $result = $this->orderService->rejectPayment($id);
        $order = $this->orderService->getOrderById($id);

        if ($result) {
            // Event'i tetikle
            event(new Event($order, $this->user));
        }

        return $result;
    }
}
