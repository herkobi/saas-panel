<?php

namespace App\Actions\Admin\Tools\Orderstatus;

use App\Models\Orderstatus;
use App\Services\Admin\Tools\OrderstatusService;
use App\Events\Admin\Tools\Orderstatus\Create as Event;
use App\Traits\AuthUser;

class Create
{
    use AuthUser;

    protected $orderstatusService;

    public function __construct(OrderstatusService $orderstatusService)
    {
        $this->orderstatusService = $orderstatusService;
        $this->initializeAuthUser();
    }

    public function execute(array $data): Orderstatus
    {
        $orderstatus = $this->orderstatusService->createOrderstatus($data);
        event(new Event($orderstatus, $this->user));
        return $orderstatus;
    }
}
