<?php

namespace App\Events\Admin\Settings\Currency;

use App\Models\Currency;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Create
{
    use Dispatchable, SerializesModels;

    public $currency;
    public $createdBy;

    public function __construct(Currency $currency, Authenticatable $createdBy)
    {
        $this->currency = $currency;
        $this->createdBy = $createdBy;
    }
}
