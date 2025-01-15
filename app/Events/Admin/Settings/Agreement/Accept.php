<?php

namespace App\Events\Admin\Settings\Agreement;

use App\Models\Agreement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Accept
{
    use Dispatchable, SerializesModels;

    public $agreement;
    public $acceptedBy;

    public function __construct(Agreement $agreement, Authenticatable $acceptedBy)
    {
        $this->agreement = $agreement;
        $this->acceptedBy = $acceptedBy;
    }
}
