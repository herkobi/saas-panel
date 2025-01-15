<?php

namespace App\Services\Admin\Accounts;

use App\Models\AccountGroup;
use App\Repositories\AccountGroupRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class AccountGroupService
{
    protected $repository;

    public function __construct(AccountGroupRepository $repository)
    {
        $this->repository = $repository;
    }
    public function getAllAccountGroups(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }
    public function getAccountGroupById(string $id): AccountGroup
    {
        return $this->repository->getAccountGroupById($id);
    }
    public function createAccountGroup(array $data): AccountGroup
    {
        return $this->repository->create($data);
    }
    public function updateAccountGroup(string $id, array $data): AccountGroup
    {
        return $this->repository->update($id, $data);
    }
    public function deleteAccountGroup(string $id): void
    {
        $this->repository->delete($id);
    }

}
