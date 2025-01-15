<?php

namespace App\Actions\Admin\Tools\Orderstatus;

use App\Services\Admin\Tools\OrderstatusService;
use App\Events\Admin\Tools\Orderstatus\Delete as Event;
use App\Models\Orderstatus;
use App\Traits\AuthUser;

class Delete
{
    use AuthUser;

    protected $orderstatusService;

    public function __construct(OrderstatusService $orderstatusService)
    {
        $this->orderstatusService = $orderstatusService;
        $this->initializeAuthUser();
    }

    public function execute(string $id): Orderstatus
    {
        $orderstatus = $this->orderstatusService->getOrderstatusById($id);
        $this->orderstatusService->deleteOrderstatus($id);
        event(new Event($orderstatus, $this->user));
        return $orderstatus;
    }
}
