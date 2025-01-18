<?php

namespace App\Events\User\Tenant;

use App\Models\Tenant;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Enums\AccountStatus;

class StatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Tenant $tenant,
        public AccountStatus $oldStatus,
        public AccountStatus $newStatus,
        public Authenticatable $changedBy
    ) {}
}
