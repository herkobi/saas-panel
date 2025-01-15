<?php

namespace App\Actions\Admin\Tools\Tax;

use App\Services\Admin\Tools\TaxService;
use App\Events\Admin\Tools\Tax\Update as Event;
use App\Models\Tax;
use App\Traits\AuthUser;

class Update
{
    use AuthUser;

    protected $taxService;

    public function __construct(TaxService $taxService)
    {
        $this->taxService = $taxService;
        $this->initializeAuthUser();
    }

    public function execute(string $id, array $data): Tax
    {
        $oldTax = $this->taxService->getTaxById($id);
        $tax = $this->taxService->updateTax($id, $data);
        $newTax = $this->taxService->getTaxById($id);
        event(new Event($tax, $this->user, $oldTax, $newTax));
        return $newTax;
    }
}
