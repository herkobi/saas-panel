<?php

namespace App\Services\Admin\Tools;

use App\Repositories\OrderstatusRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Orderstatus;
use Illuminate\Database\Eloquent\Collection;

class OrderstatusService
{
    protected $repository;

    public function __construct(OrderstatusRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllOrderstatuses(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    public function getActiveOrderstatuses(): Collection
    {
        return $this->repository->getActiveOrderstatuses();
    }

    public function getOrderstatusById(string $id, bool $withoutGlobalScope = false): Orderstatus
    {
        return $this->repository->getById($id, $withoutGlobalScope);
    }

    public function getOrderstatusByCode(string $code): Orderstatus
    {
        return $this->repository->getOrderstatusByCode($code);
    }

    public function createOrderstatus(array $data): Orderstatus
    {
        return $this->repository->create($data);
    }

    public function updateOrderstatus(string $id, array $data): Orderstatus
    {
        return $this->repository->update($id, $data);
    }

    public function deleteOrderstatus(string $id): void
    {
        $this->repository->delete($id);
    }
}
