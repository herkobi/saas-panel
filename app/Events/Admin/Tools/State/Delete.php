<?php

namespace App\Events\Admin\Tools\State;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\State;
use Illuminate\Contracts\Auth\Authenticatable;

class Delete
{
    use Dispatchable, SerializesModels;

    public $state;
    public $deletedBy;

    public function __construct(State $state, Authenticatable $deletedBy)
    {
        $this->state = $state;
        $this->deletedBy = $deletedBy;
    }
}
