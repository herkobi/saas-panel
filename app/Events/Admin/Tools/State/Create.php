<?php

namespace App\Events\Admin\Tools\State;

use App\Models\State;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Create
{
    use Dispatchable, SerializesModels;

    public $state;
    public $createdBy;

    public function __construct(State $state, Authenticatable $createdBy)
    {
        $this->state = $state;
        $this->createdBy = $createdBy;
    }
}
