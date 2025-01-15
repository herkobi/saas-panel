<?php

namespace App\Actions\Admin\AccountGroup;

use App\Services\Admin\Accounts\AccountGroupService;
use App\Events\Admin\AccountGroup\Delete as Event;
use App\Traits\AuthUser;

class Delete
{
    use AuthUser;

    protected $accountGroupService;

    public function __construct(AccountGroupService $accountGroupService)
    {
        $this->accountGroupService = $accountGroupService;
        $this->initializeAuthUser();
    }

    public function execute(string $id): bool
    {
        $accountgroup = $this->accountGroupService->getAccountGroupById($id);

        if ($accountgroup->users()->count() > 0) {
            return false;
        }

        $this->accountGroupService->deleteAccountGroup($id);
        event(new Event($accountgroup, $this->user));
        return true;
    }
}
