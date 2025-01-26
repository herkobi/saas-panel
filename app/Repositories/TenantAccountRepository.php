<?php

namespace App\Repositories;

use App\Models\Account;

class TenantAccountRepository extends BaseRepository
{
    protected $model = Account::class;

    public function createAccount(array $data)
    {
        return $this->model::create($data);
    }

    public function updateAccount(string $id, array $data): Account
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

    public function getAccount(string $id): Account
    {
        return $this->model::with(['state', 'country'])->findOrFail($id);
    }
}
