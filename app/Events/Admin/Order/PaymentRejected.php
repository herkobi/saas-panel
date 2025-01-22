<?php

namespace App\Events\Admin\Order;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use Illuminate\Contracts\Auth\Authenticatable;

class PaymentRejected
{
    use Dispatchable, SerializesModels;

    public $order;
    public $rejectedBy;

    public function __construct(Order $order, Authenticatable $rejectedBy)
    {
        $this->order = $order;
        $this->rejectedBy = $rejectedBy;
    }
}
