<?php

namespace App\Services\Admin\Settings;

use App\Repositories\CurrencyRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Currency;

class CurrencyService
{
    protected $repository;

    public function __construct(CurrencyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllCurrencies(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    public function getCurrencyById(string $id, bool $withoutGlobalScope = false): Currency
    {
        return $this->repository->getById($id, $withoutGlobalScope);
    }

    public function createCurrency(array $data): Currency
    {
        return $this->repository->create($data);
    }

    public function updateCurrency(string $id, array $data): Currency
    {
        return $this->repository->update($id, $data);
    }

    public function deleteCurrency(string $id): void
    {
        $this->repository->delete($id);
    }
}
