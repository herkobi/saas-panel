<?php

namespace App\Actions\Admin\Tools\Currency;

use App\Services\Admin\Tools\CurrencyService;
use App\Events\Admin\Tools\Currency\Update as Event;
use App\Models\Currency;
use App\Traits\AuthUser;

class Update
{
    use AuthUser;

    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
        $this->initializeAuthUser();
    }

    public function execute(string $id, array $data): Currency
    {
        $oldCurrency = $this->currencyService->getCurrencyById($id);
        $currency = $this->currencyService->updateCurrency($id, $data);
        $newCurrency = $this->currencyService->getCurrencyById($id);
        event(new Event($currency, $this->user, $oldCurrency, $newCurrency));
        return $currency;
    }
}
