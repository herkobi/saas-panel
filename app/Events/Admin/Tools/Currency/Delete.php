<?php

namespace App\Events\Admin\Tools\Currency;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Currency;
use Illuminate\Contracts\Auth\Authenticatable;

class Delete
{
    use Dispatchable, SerializesModels;

    public $currency;
    public $deletedBy;

    public function __construct(Currency $currency, Authenticatable $deletedBy)
    {
        $this->currency = $currency;
        $this->deletedBy = $deletedBy;
    }
}
