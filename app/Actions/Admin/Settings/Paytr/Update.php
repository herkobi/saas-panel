<?php

namespace App\Actions\Admin\Settings\Paytr;

use App\Services\Admin\Settings\PaytrService;
use App\Events\Admin\Settings\Paytr\Update as Event;
use App\Models\Setting;
use App\Traits\AuthUser;

class Update
{
    use AuthUser;

    protected $paytrService;

    public function __construct(PaytrService $paytrService)
    {
        $this->paytrService = $paytrService;
        $this->initializeAuthUser();
    }

    public function execute(array $data): Setting
    {
        $oldPaytr = $this->paytrService->getPaytrData();
        $paytrData = json_encode($data);
        $paytr = $this->paytrService->updatePaytrData($paytrData);
        $newPaytr = $this->paytrService->getPaytrData();

        event(new Event($paytr, $this->user, $oldPaytr, $newPaytr));
        return $paytr;
    }
}
