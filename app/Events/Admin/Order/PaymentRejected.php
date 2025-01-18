<?php

namespace App\Events\Admin\Order;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentRejected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Order $order,
        public User $user
    ) {}
}
