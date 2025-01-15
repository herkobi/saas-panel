<?php

namespace App\Services\Admin\Tools;

use App\Repositories\CurrencyRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Collection;

class CurrencyService
{
    protected $repository;

    public function __construct(CurrencyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllCurrencies(): LengthAwarePaginator
    {
        return $this->repository->getAllCurrencies();
    }

    public function getActiveCurrencies(): Collection
    {
        return $this->repository->getActiveCurrencies();
    }

    public function getCurrencyById(string $id, bool $withoutGlobalScope = false): Currency
    {
        return $this->repository->getById($id, $withoutGlobalScope);
    }

    public function getCurrencyByCode(string $code): Currency
    {
        return $this->repository->getByCode($code);
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
