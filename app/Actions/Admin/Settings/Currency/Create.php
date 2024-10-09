<?php

namespace App\Actions\Admin\Settings\Currency;

use App\Models\Currency;
use App\Services\Admin\Settings\CurrencyService;
use App\Events\Admin\Settings\Currency\Create as Event;
use App\Traits\AuthUser;

class Create
{
    use AuthUser;

    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
        $this->initializeAuthUser();
    }

    public function execute(array $data): Currency
    {
        $currency = $this->currencyService->createCurrency($data);
        event(new Event($currency, $this->user));
        return $currency;
    }
}
