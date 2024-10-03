<?php

namespace App\Events\Admin\Plans;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Plan;
use Illuminate\Contracts\Auth\Authenticatable;

class Delete
{
    use Dispatchable, SerializesModels;

    public $plan;
    public $deletedBy;

    public function __construct(Plan $plan, Authenticatable $deletedBy)
    {
        $this->plan = $plan;
        $this->deletedBy = $deletedBy;
    }
}
