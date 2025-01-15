<?php

namespace App\Events\Admin\Settings\Paytr;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;
use Illuminate\Contracts\Auth\Authenticatable;

class Update
{
    use Dispatchable, SerializesModels;

    public $paytr;
    public $changedBy;
    public $oldPaytr;
    public $newPaytr;

    public function __construct(Setting $paytr, Authenticatable $changedBy, string $oldPaytr, string $newPaytr)
    {
        $this->paytr = $paytr;
        $this->changedBy = $changedBy;
        $this->oldPaytr = $oldPaytr;
        $this->newPaytr = $newPaytr;
    }
}
