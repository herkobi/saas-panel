<?php

namespace App\Services\User\Account;

use App\Models\UserAccount;
use App\Repositories\UserAccountRepository;

class UserAccountService
{
    protected $repository;

    public function __construct(UserAccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAccount(string $id): UserAccount
    {
        return $this->repository->getAccount($id);
    }

    public function updateAccount(string $id, array $data): UserAccount
    {
        return $this->repository->updateUserAccount($id, $data);
    }

}
