<?php

namespace App\Actions\Admin\Tools\Tax;

use App\Models\Tax;
use App\Services\Admin\Tools\TaxService;
use App\Events\Admin\Tools\Tax\Create as Event;
use App\Traits\AuthUser;

class Create
{
    use AuthUser;

    protected $taxService;

    public function __construct(TaxService $taxService)
    {
        $this->taxService = $taxService;
        $this->initializeAuthUser();
    }

    public function execute(array $data): Tax
    {
        $tax = $this->taxService->createTax($data);
        event(new Event($tax, $this->user));
        return $tax;
    }
}
