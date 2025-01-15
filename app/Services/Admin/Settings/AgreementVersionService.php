<?php

namespace App\Services\Admin\Settings;

use App\Repositories\AgreementVersionRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\AgreementVersion;

class AgreementVersionService
{
    protected $repository;

    public function __construct(AgreementVersionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllAgreementVersions(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    public function getAgreementVersionById(string $id, bool $withoutGlobalScope = false): AgreementVersion
    {
        return $this->repository->getById($id, $withoutGlobalScope);
    }

    public function createAgreementVersion(string  $id, array $data): AgreementVersion
    {
        return $this->repository->createAgreementVersion($id, $data);
    }

    public function updateAgreementVersion(string $id, array $data): AgreementVersion
    {
        return $this->repository->update($id, $data);
    }

    public function deleteAgreementVersion(string $id): void
    {
        $this->repository->delete($id);
    }

    public function publishVersion(string $id, array $data): void
    {
        $this->repository->publishVersion($id, $data);
    }
}
