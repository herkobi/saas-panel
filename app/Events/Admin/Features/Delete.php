<?php

namespace App\Events\Admin\Features;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Feature;
use Illuminate\Contracts\Auth\Authenticatable;

class Delete
{
    use Dispatchable, SerializesModels;

    public $feature;
    public $deletedBy;

    public function __construct(Feature $feature, Authenticatable $deletedBy)
    {
        $this->feature = $feature;
        $this->deletedBy = $deletedBy;
    }
}
