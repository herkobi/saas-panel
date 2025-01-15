<?php

namespace App\Services\Admin\Settings;

use App\Repositories\BacsRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Bacs;
use Illuminate\Database\Eloquent\Collection;

class BacsService
{
    protected $repository;

    public function __construct(BacsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllBacs(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    public function getBacsById(string $id, bool $withoutGlobalScope = false): Bacs
    {
        return $this->repository->getById($id, $withoutGlobalScope);
    }

    public function getActiveBacs(): Collection
    {
        return $this->repository->getActive();
    }

    public function createBacs(array $data): Bacs
    {
        return $this->repository->createBacs($data);
    }

    public function updateBacs(string $id, array $data): Bacs
    {
        return $this->repository->updateBacs($id, $data);
    }

    public function deleteBacs(string $id): void
    {
        $this->repository->deleteBacs($id);
    }
}
