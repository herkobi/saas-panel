<?php

namespace App\Events\Admin\Features;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Feature;
use Illuminate\Contracts\Auth\Authenticatable;

class Update
{
    use Dispatchable, SerializesModels;

    public $feature;
    public $changedBy;
    public $oldFeature;
    public $newFeature;

    public function __construct(Feature $feature, Authenticatable $changedBy, string $oldFeature, string $newFeature)
    {
        $this->feature = $feature;
        $this->changedBy = $changedBy;
        $this->oldFeature = $oldFeature;
        $this->newFeature = $newFeature;
    }
}
