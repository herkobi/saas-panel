<?php

namespace App\Actions\User\Order;

use App\Events\User\Account\Order\PlanSwitch as Event;
use App\Models\Order;
use App\Services\Admin\Tools\OrderstatusService;
use App\Services\OrderService;
use App\Traits\AuthUser;

class PlanSwitch
{
    use AuthUser;

    protected $orderService;
    protected $orderStatusService;

    public function __construct(
        OrderService $orderService,
        OrderstatusService $orderStatusService
    ) {
        $this->orderService = $orderService;
        $this->orderStatusService = $orderStatusService;
        $this->initializeAuthUser();
    }

    public function execute(array $data): ?Order
    {
        $orderData = [
            'user_id' => $this->user->id,
            'tenant_id' => $this->user->tenant_id,
            'plan_id' => $data['plan_id'],
            'currency_id' => $data['currency_id'],
            'amount' => $data['amount'], // plan fark ücreti
            'payment_type' => $data['payment_method'],
            'invoice_data' => $data,
            'switch_data' => [
                'old_plan_id' => $this->user->tenant->subscription->plan_id,
                'immediately' => true
            ],
            'orderstatus_id' => $this->orderStatusService->getOrderstatusByCode('PENDING_PAYMENT')->id
        ];

        $order = $this->orderService->createSwitchOrder($orderData);

        if ($order) {
            event(new Event($order, $this->user));
        }

        return $order;
    }
}
