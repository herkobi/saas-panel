<?php

namespace App\Services\User\Account;

use App\Models\InvoiceDetail;
use App\Repositories\InvoiceRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class InvoiceService
{
    protected $repository;

    public function __construct(InvoiceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getUserData(): LengthAwarePaginator
    {
        return $this->repository->getUserData();
    }

    public function getInvoiceDetail(string $id): InvoiceDetail
    {
        return $this->repository->getInvoiceDetail($id);
    }

    public function createInvoiceDetail(array $data): InvoiceDetail
    {
        return $this->repository->createInvoiceDetail($data);
    }

    public function updateInvoiceDetail(string $id, array $data): InvoiceDetail
    {
        return $this->repository->updateInvoiceDetail($id, $data);
    }

    public function deleteInvoiceDetail(string $id): bool|null
    {
        return $this->repository->deleteInvoiceDetail($id);
    }

}
