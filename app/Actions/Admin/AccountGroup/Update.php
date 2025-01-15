<?php

namespace App\Actions\Admin\AccountGroup;

use App\Services\Admin\Accounts\AccountGroupService;
use App\Events\Admin\AccountGroup\Update as Event;
use App\Models\AccountGroup;
use App\Traits\AuthUser;

class Update
{
    use AuthUser;

    protected $accountGroupService;

    public function __construct(AccountGroupService $accountGroupService)
    {
        $this->accountGroupService = $accountGroupService;
        $this->initializeAuthUser();
    }

    public function execute(string $id, array $data): AccountGroup
    {
        $oldAccountGroup = $this->accountGroupService->getAccountGroupById($id);
        $accountgroup = $this->accountGroupService->updateAccountGroup($id, $data);
        $newAccountGroup = $this->accountGroupService->getAccountGroupById($id);
        event(new Event($accountgroup, $this->user, $oldAccountGroup, $newAccountGroup));
        return $accountgroup;
    }
}
