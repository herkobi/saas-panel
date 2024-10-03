<?php

namespace App\Events\Admin\Plans;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Plan;
use Illuminate\Contracts\Auth\Authenticatable;

class Update
{
    use Dispatchable, SerializesModels;

    public $plan;
    public $changedBy;
    public $oldPlan;
    public $newPlan;

    public function __construct(Plan $plan, Authenticatable $changedBy, string $oldPlan, string $newPlan)
    {
        $this->plan = $plan;
        $this->changedBy = $changedBy;
        $this->oldPlan = $oldPlan;
        $this->newPlan = $newPlan;
    }
}
