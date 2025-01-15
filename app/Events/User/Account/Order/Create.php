<?php

namespace App\Events\User\Account\Order;

use App\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Create
{
    use Dispatchable, SerializesModels;

    public $order;
    public $createdBy;

    public function __construct(Order $order, Authenticatable $createdBy)
    {
        $this->order = $order;
        $this->createdBy = $createdBy;
    }
}
