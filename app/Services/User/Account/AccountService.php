<?php

namespace App\Services\User\Account;

use App\Models\Account;
use App\Repositories\TenantAccountRepository;

class AccountService
{
    protected $repository;

    public function __construct(TenantAccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAccount(string $id): Account
    {
        return $this->repository->getAccount($id);
    }

    public function updateAccount(string $id, array $data): Account
    {
        return $this->repository->updateAccount($id, $data);
    }

}
