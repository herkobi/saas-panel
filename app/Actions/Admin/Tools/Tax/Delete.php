<?php

namespace App\Actions\Admin\Tools\Tax;

use App\Services\Admin\Tools\TaxService;
use App\Events\Admin\Tools\Tax\Delete as Event;
use App\Models\Tax;
use App\Traits\AuthUser;

class Delete
{
    use AuthUser;

    protected $taxService;

    public function __construct(TaxService $taxService)
    {
        $this->taxService = $taxService;
        $this->initializeAuthUser();
    }

    public function execute(string $id): Tax
    {
        $tax = $this->taxService->getTaxById($id);
        $this->taxService->deleteTax($id);
        event(new Event($tax, $this->user));
        return $tax;
    }
}
