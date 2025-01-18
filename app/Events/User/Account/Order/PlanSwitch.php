<?php

namespace App\Events\User\Account\Order;

use App\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class PlanSwitch
{
    use Dispatchable, SerializesModels;

    public $order;
    public $switchedBy;

    public function __construct(Order $order, Authenticatable $switchedBy)
    {
        $this->order = $order;
        $this->switchedBy = $switchedBy;
    }
}
