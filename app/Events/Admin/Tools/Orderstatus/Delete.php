<?php

namespace App\Events\Admin\Tools\Orderstatus;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Orderstatus;
use Illuminate\Contracts\Auth\Authenticatable;

class Delete
{
    use Dispatchable, SerializesModels;

    public $orderstatus;
    public $deletedBy;

    public function __construct(Orderstatus $orderstatus, Authenticatable $deletedBy)
    {
        $this->orderstatus = $orderstatus;
        $this->deletedBy = $deletedBy;
    }
}
