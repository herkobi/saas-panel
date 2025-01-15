<?php

namespace App\Repositories;

use App\Enums\AgreementVersionStatus;
use App\Enums\Status;
use App\Enums\UserType;
use App\Models\Agreement;
use App\Models\AgreementUser;
use App\Models\AgreementVersion;
use App\Traits\AuthUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class AgreementRepository extends BaseRepository
{
    use AuthUser;
    protected $model = Agreement::class;

    public function __construct() {
        $this->initializeAuthUser();
    }

    public function getAgreementBySlug(string $slug): Agreement
    {
        return $this->model::where('slug', $slug)
            ->with(['versions' => function($query) {
                $query->orderBy('published_at', 'desc');
            }])
            ->firstOrFail();
    }

    public function getAdminAgreements(): LengthAwarePaginator
    {
        return $this->model::where('user_type', UserType::ADMIN)
            ->defaultPagination();
    }

    public function getUserAgreements(): LengthAwarePaginator
    {
        return $this->model::where('user_type', UserType::USER)
            ->defaultPagination();
    }

    public function getPaymentAgreements(): Collection
    {
        return $this->model::where('user_type', UserType::USER)
            ->where('show_on_payment', true)
            ->where('status', Status::ACTIVE)
            ->whereHas('versions', function($query) {
                $query->where('status', AgreementVersionStatus::PUBLISHED);
            })
            ->with(['versions' => function($query) {
                $query->where('status', AgreementVersionStatus::PUBLISHED)
                     ->latest('published_at');
            }])
            ->get();
    }

    public function getByIdAndType(string $id, UserType $userType): Agreement
    {
        return $this->model::where('id', $id)
            ->where('user_type', $userType)
            ->with('versions')
            ->firstOrFail();
    }

    public function getAllSignatures(): LengthAwarePaginator
    {
        return AgreementUser::with(['user', 'agreement', 'version'])
            ->select('agreement_user.*')
            ->defaultPagination();
    }

    public function getAgreementSignatures(string $id): LengthAwarePaginator
    {
        return AgreementUser::with(['user', 'version'])
            ->where('agreement_id', $id)
            ->defaultPagination();
    }

    public function acceptAgreement(Agreement $agreement, AgreementVersion $version): Agreement
    {
        $this->user->agreements()->attach($agreement->id, [
            'id' => Str::uuid(),
            'agreement_version_id' => $version->id,
            'accepted_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return $agreement;
    }
}
