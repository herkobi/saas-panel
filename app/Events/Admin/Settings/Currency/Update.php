<?php

namespace App\Events\Admin\Settings\Currency;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Currency;
use Illuminate\Contracts\Auth\Authenticatable;

class Update
{
    use Dispatchable, SerializesModels;

    public $currency;
    public $changedBy;
    public $oldCurrency;
    public $newCurrency;

    public function __construct(Currency $currency, Authenticatable $changedBy, string $oldCurrency, string $newCurrency)
    {
        $this->currency = $currency;
        $this->changedBy = $changedBy;
        $this->oldCurrency = $oldCurrency;
        $this->newCurrency = $newCurrency;
    }
}
