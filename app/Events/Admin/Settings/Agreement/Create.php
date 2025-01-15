<?php

namespace App\Events\Admin\Settings\Agreement;

use App\Models\Agreement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Create
{
    use Dispatchable, SerializesModels;

    public $agreement;
    public $createdBy;

    public function __construct(Agreement $agreement, Authenticatable $createdBy)
    {
        $this->agreement = $agreement;
        $this->createdBy = $createdBy;
    }
}
