<?php

namespace App\Services\Admin\Tools;

use App\Repositories\TaxRepository;
use App\Models\Tax;
use Illuminate\Database\Eloquent\Collection;

class TaxService
{
    protected $repository;

    public function __construct(TaxRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllTaxes(): Collection
    {
        return $this->repository->getAllTaxes();
    }

    public function getActiveTaxes(): Collection
    {
        return $this->repository->getActiveTaxes();
    }

    public function getTaxById(string $id, bool $withoutGlobalScope = false): Tax
    {
        return $this->repository->getById($id, $withoutGlobalScope);
    }

    public function createTax(array $data): Tax
    {
        return $this->repository->createTax($data);
    }

    public function updateTax(string $id, array $data): Tax
    {
        return $this->repository->updateTax($id, $data);
    }

    public function deleteTax(string $id): void
    {
        $this->repository->deleteTax($id);
    }

    public function getTaxRateByRegion(string $countryId, string $stateId)
    {
        return $this->repository->getTaxRateByRegion($countryId, $stateId);
    }

    public function getTaxesByRegion(string $countryId, ?string $stateId = null): Collection
    {
        // Eyalete özel vergileri al
        if ($stateId) {
            $taxes = $this->repository->getTaxesByRegion($countryId, $stateId);
            if ($taxes->isNotEmpty()) {
                return $taxes;
            }
        }

        // Eyalete özel vergi yoksa veya state_id null ise ülke genelindeki tüm vergileri al
        return $this->repository->getTaxesByRegion($countryId);
    }
}
