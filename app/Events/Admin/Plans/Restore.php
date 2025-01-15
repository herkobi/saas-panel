<?php

namespace App\Events\Admin\Plans;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Plan;
use Illuminate\Contracts\Auth\Authenticatable;

class Restore
{
    use Dispatchable, SerializesModels;

    public $plan;
    public $restoredBy;

    public function __construct(Plan $plan, Authenticatable $restoredBy)
    {
        $this->plan = $plan;
        $this->restoredBy = $restoredBy;
    }
}
