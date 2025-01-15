<?php

namespace App\Services\Admin\Tools;

use App\Repositories\StateRepository;
use App\Models\State;
use Illuminate\Database\Eloquent\Collection;

class StateService
{
    protected $repository;

    public function __construct(StateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllStates($country): Collection
    {
        return $this->repository->getAllStates($country);
    }

    public function getActiveStates(): Collection
    {
        return $this->repository->getActiveStates();
    }

    public function getStateById(string $id, bool $withoutGlobalScope = false): State
    {
        return $this->repository->getById($id, $withoutGlobalScope);
    }

    public function createState(array $data): State
    {
        return $this->repository->create($data);
    }

    public function updateState(string $id, array $data): State
    {
        return $this->repository->update($id, $data);
    }

    public function deleteState(string $id): void
    {
        $this->repository->delete($id);
    }

    public function getCountryStates(string $id): Collection
    {
        return $this->repository->getCountryStates($id);
    }
}
