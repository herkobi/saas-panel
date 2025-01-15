<?php

namespace App\Events\Admin\Tools\Orderstatus;

use App\Models\Orderstatus;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Create
{
    use Dispatchable, SerializesModels;

    public $orderstatus;
    public $createdBy;

    public function __construct(Orderstatus $orderstatus, Authenticatable $createdBy)
    {
        $this->orderstatus = $orderstatus;
        $this->createdBy = $createdBy;
    }
}
