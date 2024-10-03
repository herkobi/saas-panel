<?php

namespace App\Events\Admin\Plans;

use App\Models\Plan;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Create
{
    use Dispatchable, SerializesModels;

    public $plan;
    public $createdBy;

    public function __construct(Plan $plan, Authenticatable $createdBy)
    {
        $this->plan = $plan;
        $this->createdBy = $createdBy;
    }
}
