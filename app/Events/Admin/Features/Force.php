<?php

namespace App\Events\Admin\Features;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use LucasDotVin\Soulbscription\Models\Feature;
use Illuminate\Contracts\Auth\Authenticatable;

class Force
{
    use Dispatchable, SerializesModels;

    public $feature;
    public $forcedBy;

    public function __construct(Feature $feature, Authenticatable $forcedBy)
    {
        $this->feature = $feature;
        $this->forcedBy = $forcedBy;
    }
}
