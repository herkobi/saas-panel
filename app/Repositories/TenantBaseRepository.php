<?php

namespace App\Repositories;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class TenantBaseRepository extends BaseRepository
{
    protected $model;

    public function getAll(): LengthAwarePaginator
    {
        return $this->model::defaultPagination();
    }

    public function getById(string $id, bool $withoutTenantScope = false): Model
    {
        $query = $withoutTenantScope
            ? $this->model::withoutGlobalScope(TenantScope::class)
            : $this->model::query();

        return $query->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model::create($data);
    }

    public function update(string $id, array $data): Model
    {
        $model = $this->getById($id);
        $model->update($data);
        return $model;
    }

    public function delete(string $id): void
    {
        $model = $this->getById($id);
        $model->delete();
    }
}
