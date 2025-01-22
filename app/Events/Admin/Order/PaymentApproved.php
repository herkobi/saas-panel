<?php

namespace App\Events\Admin\Order;

use App\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class PaymentApproved
{
    use Dispatchable, SerializesModels;

    public $order;
    public $approvedBy;

    public function __construct(Order $order, Authenticatable $approvedBy)
    {
        $this->order = $order;
        $this->approvedBy = $approvedBy;
    }
}
