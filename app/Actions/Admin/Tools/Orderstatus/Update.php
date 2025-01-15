<?php

namespace App\Actions\Admin\Tools\Orderstatus;

use App\Services\Admin\Tools\OrderstatusService;
use App\Events\Admin\Tools\Orderstatus\Update as Event;
use App\Models\Orderstatus;
use App\Traits\AuthUser;

class Update
{
    use AuthUser;

    protected $orderstatusService;

    public function __construct(OrderstatusService $orderstatusService)
    {
        $this->orderstatusService = $orderstatusService;
        $this->initializeAuthUser();
    }

    public function execute(string $id, array $data): Orderstatus
    {
        $oldOrderstatus = $this->orderstatusService->getOrderstatusById($id);
        $orderstatus = $this->orderstatusService->updateOrderstatus($id, $data);
        $newOrderstatus = $this->orderstatusService->getOrderstatusById($id);
        event(new Event($orderstatus, $this->user, $oldOrderstatus, $newOrderstatus));
        return $orderstatus;
    }
}
