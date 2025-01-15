<?php

namespace App\Actions\User\Order;

use App\Models\Order;
use App\Services\OrderService;
use App\Events\User\Account\Order\Create as Event;
use App\Traits\AuthUser;

class Create
{
    use AuthUser;

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
        $this->initializeAuthUser();
    }

    public function execute(array $data): ?Order
    {
        $order = $this->orderService->createPaymentOrder($data);
        event(new Event($order, $this->user));
        return $order;
    }
}
