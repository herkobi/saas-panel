<?php

namespace App\Actions\Admin\AccountGroup;

use App\Models\AccountGroup;
use App\Services\Admin\Accounts\AccountGroupService;
use App\Events\Admin\AccountGroup\Create as Event;
use App\Traits\AuthUser;

class Create
{
    use AuthUser;

    protected $accountGroupService;

    public function __construct(AccountGroupService $accountGroupService)
    {
        $this->accountGroupService = $accountGroupService;
        $this->initializeAuthUser();
    }

    public function execute(array $data): AccountGroup
    {
        $accountgroup = $this->accountGroupService->createAccountGroup($data);
        event(new Event($accountgroup, $this->user));
        return $accountgroup;
    }
}
