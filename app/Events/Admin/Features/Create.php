<?php

namespace App\Events\Admin\Features;

use App\Models\Feature;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Create
{
    use Dispatchable, SerializesModels;

    public $feature;
    public $createdBy;

    public function __construct(Feature $feature, Authenticatable $createdBy)
    {
        $this->feature = $feature;
        $this->createdBy = $createdBy;
    }
}
