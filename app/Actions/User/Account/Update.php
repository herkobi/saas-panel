<?php

namespace App\Actions\User\Account;

use App\Models\Account;
use App\Events\User\Account\Account\Account as Event;
use App\Services\User\Account\AccountService as Service;
use App\Traits\AuthUser;

class Update
{
    use AuthUser;

    protected $postService;

    /**
     * GetOne işlemi için gerekli Service bağımlılığı enjekte edilir.
     *
     * @param Service $postService
     */
    public function __construct(Service $postService)
    {
        $this->postService = $postService;
        $this->initializeAuthUser();
    }

    /**
     * Belirtilen ID'ye sahip kullanıcının profil bilgilerini günceller.
     *
     * @param string $id Getirilecek kullanıcı ID'si
     * @return mixed Güncellenecek veriler
     */
    public function execute(string $id, array $data): Account
    {
        $oldData = $this->postService->getAccount($id);
        $updatedData = $this->postService->updateAccount($id, $data);
        $newData = $this->postService->getAccount($id);
        event(new Event($this->user, $oldData, $newData));
        return $updatedData;
    }
}
