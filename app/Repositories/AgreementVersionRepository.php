<?php

namespace App\Repositories;

use App\Enums\AgreementVersionStatus;
use App\Models\AgreementVersion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AgreementVersionRepository extends BaseRepository
{
    protected $model = AgreementVersion::class;

    public function createAgreementVersion(string $id, array $data): AgreementVersion
    {
        return $this->model::create([
            'agreement_id' => $id,
            'version' => $data['version'],
            'content' => $data['content']
        ]);
    }

    public function publishVersion(string $id, array $data): void
    {
        $version = $this->getById($id);

        if ($version->status !== AgreementVersionStatus::DRAFT) {
            throw ValidationException::withMessages([
                'status' => 'Sadece taslak durumundaki versiyonlar yayınlanabilir.'
            ]);
        }

        DB::transaction(function () use ($version, $data) {
            // Önce mevcut yayındaki versiyonu arşivle
            $version->agreement->versions()
                ->where('status', AgreementVersionStatus::PUBLISHED)
                ->update([
                    'status' => AgreementVersionStatus::ARCHIVED,
                    'updated_at' => now()
                ]);

            // Yeni versiyonu yayınla
            $version->update([
                'status' => AgreementVersionStatus::PUBLISHED,
                'published_at' => now(),
                'require_acceptance' => $data['require_acceptance'],
                'block_access' => $data['block_access'],
                'send_notification' => $data['send_notification']
            ]);
        });
    }

}
