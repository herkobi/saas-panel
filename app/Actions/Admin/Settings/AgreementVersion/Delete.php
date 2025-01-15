<?php

namespace App\Actions\Admin\Settings\AgreementVersion;

use App\Services\Admin\Settings\AgreementVersionService;
use App\Events\Admin\Settings\AgreementVersion\Delete as Event;
use App\Models\AgreementVersion;
use App\Traits\AuthUser;

class Delete
{
    use AuthUser;

    protected $agreementversionService;

    public function __construct(AgreementVersionService $agreementversionService)
    {
        $this->agreementversionService = $agreementversionService;
        $this->initializeAuthUser();
    }

    public function execute(string $id): AgreementVersion
    {
        $agreementversion = $this->agreementversionService->getAgreementVersionById($id);
        $this->agreementversionService->deleteAgreementVersion($id);
        event(new Event($agreementversion, $this->user));
        return $agreementversion;
    }
}
