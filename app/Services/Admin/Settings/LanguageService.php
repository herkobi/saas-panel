<?php

namespace App\Services\Admin\Settings;

use App\Repositories\LanguageRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Language;

class LanguageService
{
    protected $repository;

    public function __construct(LanguageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllLanguages(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    public function getLanguageById(string $id, bool $withoutGlobalScope = false): Language
    {
        return $this->repository->getById($id, $withoutGlobalScope);
    }

    public function createLanguage(array $data): Language
    {
        return $this->repository->create($data);
    }

    public function updateLanguage(string $id, array $data): Language
    {
        return $this->repository->update($id, $data);
    }

    public function deleteLanguage(string $id): void
    {
        $this->repository->delete($id);
    }
}