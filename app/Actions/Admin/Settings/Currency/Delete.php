<?php

namespace App\Actions\Admin\Settings\Currency;

use App\Services\Admin\Settings\CurrencyService;
use App\Events\Admin\Settings\Currency\Delete as Event;
use App\Models\Currency;
use App\Traits\AuthUser;

class Delete
{
    use AuthUser;

    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
        $this->initializeAuthUser();
    }

    public function execute(string $id): Currency
    {
        $currency = $this->currencyService->getCurrencyById($id);
        $this->currencyService->deleteCurrency($id);
        event(new Event($currency, $this->user));
        return $currency;
    }
}
