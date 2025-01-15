<?php

namespace App\Repositories;

use App\Models\UserAccount;

class UserAccountRepository extends BaseRepository
{
    protected $model = UserAccount::class;

    public function createUserAccount(array $data)
    {
        return $this->model::create($data);
    }

    public function updateUserAccount(string $id, array $data): UserAccount
    {
        $account = $this->getAccount($id);
        $account->update([
            'invoice_name' => $data['invoice_name'] ?? null,
            'tax_number' => $data['tax_number'] ?? null,
            'tax_office' => $data['tax_office'] ?? null,
            'mersis' => $data['mersis'] ?? null,
            'address' => $data['address'] ?? null,
            'zip_code' => $data['zip_code'] ?? null,
            'state_id' => $data['state_id'] ?? null,
            'district' => $data['district'] ?? null,
            'country_id' => $data['country_id'] ?? null,
            'email' => $data['email'] ?? null,
            'kep' => $data['kep'] ?? null,
            'phone' => $data['phone'] ?? null,
            'gsm' => $data['gsm'] ?? null,
        ]);

        return $account;
    }

    public function getAccount(string $id): UserAccount
    {
        return $this->model::with(['state', 'country'])->findOrFail($id);
    }
}
