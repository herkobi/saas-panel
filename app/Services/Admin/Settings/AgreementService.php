<?php

namespace App\Services\Admin\Settings;

use App\Enums\UserType;
use App\Traits\AuthUser;
use App\Models\Agreement;
use App\Enums\AgreementVersionStatus;
use App\Repositories\AgreementRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AgreementService
{
    use AuthUser;

    protected $repository;

    public function __construct(AgreementRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAdminAgreements(): LengthAwarePaginator
    {
        return $this->repository->getAdminAgreements();
    }

    public function getUserAgreements(): LengthAwarePaginator
    {
        return $this->repository->getUserAgreements();
    }

    public function getPaymentAgreements(): Collection
    {
        return $this->repository->getPaymentAgreements();
    }

    public function getAgreementByIdAndType(string $id, UserType $userType): Agreement
    {
        return $this->repository->getByIdAndType($id, $userType);
    }

    public function getAgreementById(string $id, bool $withoutGlobalScope = false): Agreement
    {
        return $this->repository->getById($id, $withoutGlobalScope);
    }

    public function getAgreementBySlug(string $slug): Agreement
    {
        return $this->repository->getAgreementBySlug($slug);
    }

    public function createAgreement(array $data): Agreement
    {
        return $this->repository->create($data);
    }

    public function updateAgreement(string $id, array $data): Agreement
    {
        return $this->repository->update($id, $data);
    }

    public function deleteAgreement(string $id): void
    {
        $this->repository->delete($id);
    }

    public function getAllSignatures(): LengthAwarePaginator
    {
        return $this->repository->getAllSignatures();
    }

    public function getAgreementSignatures(string $agreementId): LengthAwarePaginator
    {
        return $this->repository->getAgreementSignatures($agreementId);
    }

    public function acceptAgreement(array $data): Agreement
    {
        $agreement = $this->repository->getById($data['agreement_id']);
        $version = $agreement->versions()
            ->where('id', $data['version_id'])
            ->where('status', AgreementVersionStatus::PUBLISHED)
            ->firstOrFail();

        if (!$version) {
            throw new \InvalidArgumentException('Sözleşmenin yayınlanmış bir versiyonu bulunamadı.');
        }

        if ($agreement->user_type !== $this->user->type) {
            throw new \InvalidArgumentException('Bu sözleşmeyi kabul etme yetkiniz yok.');
        }

        if ($this->user->hasAcceptedVersion($version)) {
            throw new \InvalidArgumentException('Bu sözleşme versiyonunu zaten kabul ettiniz.');
        }

        return $this->repository->acceptAgreement($agreement, $version);
    }
}
