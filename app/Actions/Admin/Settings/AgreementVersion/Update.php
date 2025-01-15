<?php

namespace App\Actions\Admin\Settings\AgreementVersion;

use App\Services\Admin\Settings\AgreementVersionService;
use App\Events\Admin\Settings\AgreementVersion\Update as Event;
use App\Models\AgreementVersion;
use App\Traits\AuthUser;

class Update
{
    use AuthUser;

    protected $agreementversionService;

    public function __construct(AgreementVersionService $agreementversionService)
    {
        $this->agreementversionService = $agreementversionService;
        $this->initializeAuthUser();
    }

    public function execute(string $id, array $data): AgreementVersion
    {
        $oldAgreementVersion = $this->agreementversionService->getAgreementVersionById($id);
        $agreementversion = $this->agreementversionService->updateAgreementVersion($id, $data);
        $newAgreementVersion = $this->agreementversionService->getAgreementVersionById($id);
        event(new Event($agreementversion, $this->user, $oldAgreementVersion, $newAgreementVersion));
        return $agreementversion;
    }
}
