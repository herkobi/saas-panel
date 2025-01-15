<?php

namespace App\Events\Admin\Plans;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Plan;
use Illuminate\Contracts\Auth\Authenticatable;

class Force
{
    use Dispatchable, SerializesModels;

    public $plan;
    public $forcedBy;

    public function __construct(Plan $plan, Authenticatable $forcedBy)
    {
        $this->plan = $plan;
        $this->forcedBy = $forcedBy;
    }
}
