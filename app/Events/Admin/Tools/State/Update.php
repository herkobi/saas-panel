<?php

namespace App\Events\Admin\Tools\State;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\State;
use Illuminate\Contracts\Auth\Authenticatable;

class Update
{
    use Dispatchable, SerializesModels;

    public $state;
    public $changedBy;
    public $oldState;
    public $newState;

    public function __construct(State $state, Authenticatable $changedBy, string $oldState, string $newState)
    {
        $this->state = $state;
        $this->changedBy = $changedBy;
        $this->oldState = $oldState;
        $this->newState = $newState;
    }
}
