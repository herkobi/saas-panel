<?php

namespace App\Events\Admin\Tools\Orderstatus;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Orderstatus;
use Illuminate\Contracts\Auth\Authenticatable;

class Update
{
    use Dispatchable, SerializesModels;

    public $orderstatus;
    public $changedBy;
    public $oldOrderstatus;
    public $newOrderstatus;

    public function __construct(Orderstatus $orderstatus, Authenticatable $changedBy, string $oldOrderstatus, string $newOrderstatus)
    {
        $this->orderstatus = $orderstatus;
        $this->changedBy = $changedBy;
        $this->oldOrderstatus = $oldOrderstatus;
        $this->newOrderstatus = $newOrderstatus;
    }
}
