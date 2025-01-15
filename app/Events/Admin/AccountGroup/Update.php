<?php

namespace App\Events\Admin\AccountGroup;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\AccountGroup;
use Illuminate\Contracts\Auth\Authenticatable;

class Update
{
    use Dispatchable, SerializesModels;

    public $accountgroup;
    public $changedBy;
    public $oldAccountGroup;
    public $newAccountGroup;

    public function __construct(AccountGroup $accountgroup, Authenticatable $changedBy, string $oldAccountGroup, string $newAccountGroup)
    {
        $this->accountgroup = $accountgroup;
        $this->changedBy = $changedBy;
        $this->oldAccountGroup = $oldAccountGroup;
        $this->newAccountGroup = $newAccountGroup;
    }
}
