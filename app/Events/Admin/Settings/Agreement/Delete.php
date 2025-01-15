<?php

namespace App\Events\Admin\Settings\Agreement;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Agreement;
use Illuminate\Contracts\Auth\Authenticatable;

class Delete
{
    use Dispatchable, SerializesModels;

    public $agreement;
    public $deletedBy;

    public function __construct(Agreement $agreement, Authenticatable $deletedBy)
    {
        $this->agreement = $agreement;
        $this->deletedBy = $deletedBy;
    }
}
