<?php

namespace App\Events\Admin\AccountGroup;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\AccountGroup;
use Illuminate\Contracts\Auth\Authenticatable;

class Delete
{
    use Dispatchable, SerializesModels;

    public $accountgroup;
    public $deletedBy;

    public function __construct(AccountGroup $accountgroup, Authenticatable $deletedBy)
    {
        $this->accountgroup = $accountgroup;
        $this->deletedBy = $deletedBy;
    }
}
