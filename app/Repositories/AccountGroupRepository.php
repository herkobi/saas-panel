<?php

namespace App\Repositories;

use App\Models\AccountGroup;

class AccountGroupRepository extends BaseRepository
{
    protected $model = AccountGroup::class;

    public function getAccountGroupById($id): AccountGroup
    {
        return $this->model::with('users')->find($id);
    }

}
