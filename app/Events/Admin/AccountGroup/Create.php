<?php

namespace App\Events\Admin\AccountGroup;

use App\Models\AccountGroup;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Create
{
    use Dispatchable, SerializesModels;

    public $accountgroup;
    public $createdBy;

    public function __construct(AccountGroup $accountgroup, Authenticatable $createdBy)
    {
        $this->accountgroup = $accountgroup;
        $this->createdBy = $createdBy;
    }
}
