<?php

namespace App\Events\User\Account\Account;

use App\Models\UserAccount;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class Account
{
    use Dispatchable, SerializesModels;

    public $account;
    public $changedBy;
    public $oldData;
    public $newData;

    public function __construct(Authenticatable $changedBy, string $oldData, string $newData)
    {
        $this->changedBy = $changedBy;
        $this->oldData = $oldData;
        $this->newData = $newData;
    }
}
