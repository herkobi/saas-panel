<?php

namespace App\Events\Admin\Features;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use LucasDotVin\Soulbscription\Models\Feature;
use Illuminate\Contracts\Auth\Authenticatable;

class Restore
{
    use Dispatchable, SerializesModels;

    public $feature;
    public $restoredBy;

    public function __construct(Feature $feature, Authenticatable $restoredBy)
    {
        $this->feature = $feature;
        $this->restoredBy = $restoredBy;
    }
}
